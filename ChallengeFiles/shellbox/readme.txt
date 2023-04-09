Hi player!

This container is used to help you receive call backs (like reverse shells) from other containers. You can also use it to probe or troubleshoot a challenge.

It has the following tools installed:
- bash
- curl
- iputils
- java
- wget
- netcat
- vim
- nano
- tmux
- python3
- socat
- sqlmap *

* Do _not_ use sqlmap's password cracking feature in this container - it will crash and/or be very slow! ^_^

Every container has strict inbound firewall rules so you can only accept reverse shells on these ports:
- 1389
- 2222
- 3333
- 4444
- 8000
- 8080

You won’t be able to establish any bind shells to containers because of the firewall rules.

Good luck, and have fun!

Note: You can report security vulnerabilities in this container to organisers for points under the “Zero (Day) Cool” Miscellaneous challenge.