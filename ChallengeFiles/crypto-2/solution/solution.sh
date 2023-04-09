#!/bin/bash
echo "Step 1: read source code and documentation to reveal vulnerable code"
echo "Step 2: connect to dev server and record output"
ciphertext=$(socat -T1 TCP4:127.0.0.1:9999 - 2&> /dev/null)
echo "Output\n$ciphertext"
echo "Step 3: Use AES-128-CBC to decrypt ciphertext (key==IV)"
echo "Decrypted: 'Please request the flag by typing FLAG into the console'"
echo "Step 4: encrypt 'FLAG' and send to service"
echo "AES-128-CBC('FLAG') -> 0011223344556677889900112233445589ccd58c14de5dd90c82b02bc3f915d0"
ciphertext=$(echo "0011223344556677889900112233445589ccd58c14de5dd90c82b02bc3f915d0"| nc localhost 9999 2&> /dev/null)
echo "Output\n$ciphertext"

echo "Step 5: Use AES-128-CBC to decrypt ciphertext (key==IV)"
echo "Decrypted: 'Note: the development server does not support FLAG delivery at this stage.'"
echo "Step 6: send the payload to the production server"
ciphertext=$(echo "0011223344556677889900112233445589ccd58c14de5dd90c82b02bc3f915d0"| nc localhost 8888 2&> /dev/null)

echo "AES-128-CBC decrypt ciphertext to reveal the flag"
echo "Output\n$ciphertext"
echo "The flag is: WACTF{use_a_random_iv_please}"