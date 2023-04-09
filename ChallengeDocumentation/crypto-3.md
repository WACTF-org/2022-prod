| metadata | <> |
|--- | --- |
| Developer Name(s) | Dono |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Crypto |
| Challenge Tier | 3 |
| Challenge Type | Filedrop |

| Player facing | <> |
|--- | --- |
| Challenge Name | ZippitidyDooDah |
| Challenge Description | While trying to hax our brand new Telsa Model 99, we've intercepted a firmware blob which we believe contains a secret file that will help us. Can you retrieve this secret file and show us the way? Oh right, and there is some Crypto of type Zip involved somehow.|
| Challenge Hint 1 | that filename seems pretty unique..... |
| Challenge Hint 2 | if you know some plaintext in that that file i wonder what you can do with it |

| Admin Facing | <> |
|--- | --- |
| Challenge Flag | WACTF{5f3158e06bc6587c5acecac647757e68c6bb84ea} |
| Challenge Vuln | An Alpine disk image has been zip encrypted. Decrypt it and retrieve secret.txt using a known plaintext attack|
---

Challenge PoC 

> This was inspired by https://programmingwithstyle.com/posts/howihackedmycar/
> Zip files password is 8PefiuZE@qCXAawMZFPDva9N

1. brew install libomp
2. Download a release of bkcrack from here https://github.com/kimci86/bkcrack/releases
3. Create a file `plain.txt` with the contents "alpine-standard-3.16.2 220706" (no space or newlines. this is from the .alpine-release file as retrieved from https://dl-cdn.alpinelinux.org/alpine/v3.16/releases/aarch64/alpine-standard-3.16.2-aarch64.iso)
4. ./bkcrack -C alpine-standard-3.16.2-aarch64.iso.bin -c .alpine-release -p plain.txt -e
5. Check the keys identified are "1f31450a c85ff17c cdd78d6a"
6. ../bkcrack -C alpine-standard-3.16.2-aarch64.iso.bin -k 1f31450a c85ff17c cdd78d6a -U decrypted.zip password1
7. Open decrypted.zip, using password1 as the password
8. Flag is in secrets.txt