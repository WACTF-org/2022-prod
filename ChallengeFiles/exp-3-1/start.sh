#!/bin/bash

cd /home/ctf;
while true; do
    socat -dd TCP4-LISTEN:1337,fork,reuseaddr EXEC:./pwnable,pty,setuid=ctf,setgid=ctf,echo=0,raw,iexten=0
done