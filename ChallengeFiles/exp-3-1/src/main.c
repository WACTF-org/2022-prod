#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <signal.h>
#include <unistd.h>
#include <string.h>
#include <ctype.h>
#include <stdbool.h>
#include <stdint.h>

static char* FLAG = NULL;
int16_t dragon, player, player_stats, dragon_stats;

inline void draw_line(char c, size_t len) {
    for (size_t i = 0; i < len; ++i) putchar(c);
}

void show_menu() {
    printf("\n");
    draw_line('=', 100);
    printf("\n");

    puts("1) Print stats\n2) Attack dragon\n3) Cast heal\n4) Skip turn\n5) Exit");

    draw_line('=', 100);
    printf("\n");
}

void parse_option() {
    int choice = 0;

    printf("\nPlease select a menu option> ");
    scanf("%d", &choice);

    switch (choice) {
        case 1:
            printf("\n");
            draw_line('=', 100);
            printf("\n");

            puts("1) Player\n2) Dragon");

            draw_line('=', 100);
            printf("\n\n");

            printf("Please select an entity to target> ");
            scanf("%d", &choice);

            if (choice == 1) {
                printf("\nPlayer health = %d\nPlayer strength = %d\n", player, player_stats);

            } else {
                if (choice != 2) goto INVALID_OPTION;
                printf("\nDragon health = %d\nDragon strength = %d\n", dragon, dragon_stats);
            }
            break;
        
        case 2:
            dragon -= player_stats;
            printf("\nYou attack the dragon for %d damage!\nDragon health = %d\n", player_stats, dragon);
            return;
        
        case 3:
            printf("\n");
            draw_line('=', 100);
            printf("\n");

            puts("1) Player\n2) Dragon");
            draw_line('=', 100);
            printf("\n\n");

            printf("Please select an entity to target> ");
            scanf("%d", &choice);
            if (choice == 1) {
                player += player_stats;
                printf("\nHealed yourself %d points.\nPlayer health = %d\n", player_stats, player);

            } else if (choice == 2) {
                dragon += dragon_stats;
                printf("\nHealed `dragon` %d points.\nDragon health = %d\n", dragon_stats, dragon);

            } else {
    INVALID_OPTION:
                puts("\nThat option is invalid.");
            }
            break;
        
        case 4:
            puts("\nYou skip your turn.");
            return;
        
        case 5:
            puts("\nAww... Goodbye!");
            exit(0);

        default:
            goto INVALID_OPTION;
    }

    return;
}

void check_events(uint32_t turn) {
  printf("\n");
  draw_line('=', 100);
  printf("\n");

  printf("Events for turn %d:\n\n", turn + 1);
  if (dragon <= 0) {
    printf(
      "You killed the beast! Here's your flag!\n"
      "----------------------------------------------------------------------------------------------------\n"
      "                                -,,,__\n"
      "                                 \\    ``~~--,,__                /   /\n"
      "                                 /              ``~~--,,_     //--//\n"
      "                      _,,,,-----,\\              ,,,,---- >   (c  c)\\\n"
      "                  ,;''            `\\,,,,----''''   ,,-'''---/   /_ ;___        -,_\n"
      "                 ( ''---,;====;,----/             (-,,_____/  /'/ `;   '''''----\\ `:.\n"
      "                 (                 '               `      (oo)/   ;~~~~~~~~~~~~~/--~\n"
      "                  `;_           ;    \\            ;   \\   `  ' ,,'\n"
      "                     ```-----...|     )___________|    )-----'''\n"
      "            Art by               \\   /             \\   \\\n"
      "              Korrath            /  /,              `\\   \\\n"
      "                               ,'---\\ \\              ,---`,;,\n"
      "                                     ```\n"
      "                             --[ %s ]--\n"
      "----------------------------------------------------------------------------------------------------\n\n\n",
      FLAG
    );
    sleep(1);
    exit(0);
  }

  if (dragon > 999) {
    if (turn % 3 && turn != 0) {
      puts("The dragon does nothing.");

    } else {
      player -= dragon_stats;
      printf("The dragon attacks you for %d damage!\nPlayer health = %d\n", dragon_stats, player);
    }

  } else {
    puts("The dragon uses `heal`!\nDragon health = 5000");
    dragon = 5000;

  }

  if (player <= 0) {
    puts("You are dead!");
    exit(0);
  }

  draw_line('=', 100);
  printf("\n");
}

void goodbye(int sig) {
    _exit(0);
}

void setup() {
    signal(SIGALRM, goodbye);
    alarm(60 * 10); // 10 minutes.
    setbuf(stdout, NULL);

    FILE *fh = fopen("flag.txt", "rb");

    if (fh == NULL) {
        puts("[!] Issue reading the flag.");
        _exit(1);
    }

    fseek(fh, 0, SEEK_END);
    long flag_len = ftell(fh);
    fseek(fh, 0, SEEK_SET);

    FLAG = (char*)malloc(flag_len + 1);

    if (FLAG == NULL) {
        puts("[!] Issue allocating memory for the flag.");
        _exit(1);
    }

    fread(FLAG, flag_len, sizeof(char), fh);
    fclose(fh);

    FLAG[flag_len] = '\0';
}

int main(int argc, const char **argv) {
    unsigned int turn = 0;
    setup();

    player = 100;
    player_stats = 50;
    dragon = 1000;
    dragon_stats = 100;

    puts(
        "----------------------------------------------------------------------------------------------------\n"
        " \n"
        " \n"
        " \n"
        "                 ______ _       ___  _____            _____ _   _ _____ _____ _____\n"
        "                 |  ___| |     / _ \\|  __ \\          |  _  | | | |  ___/  ___|_   _|\n"
        "                 | |_  | |    / /_\\ \\ |  \\/  ______  | | | | | | | |__ \\ `--.  | |\n"
        "                 |  _| | |    |  _  | | __  |______| | | | | | | |  __| `--. \\ | |\n"
        "                 | |   | |____| | | | |_\\ \\          \\ \\/' / |_| | |___/\\__/ / | |\n"
        "                 \\_|   \\_____/\\_| |_/\\____/           \\_/\\_\\\\___/\\____/\\____/  \\_/\n"
        "                                                                    version 1.33.7\n"
        " \n"
        " \n"
        "----------------------------------------------------------------------------------------------------"
    );

    do {
        show_menu();
        parse_option();
        check_events(turn++);
    } while (turn < 0xFFFF);

    puts("\nYou've exceeded the maxiumum turn limit...\nPlease try again.");
    return 0;
}