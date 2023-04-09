| metadata                                  | <> |
|-------------------------------------------|----------------------------------------|
| Developer Name(s)                         | Rhys |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category                        | Web |
| Challenge Tier                            | 3-2 |
| Challenge Type                            | Container |

| Player facing         | <>|
|-------------------------------------------|-------------------------------------------------------|
| Challenge Name        | G-BUCKS |
| Challenge Description | I made a site to generate free G-BUCKS.  `http://web-3-2` |
| Challenge Hint 1      | /?debug |
| Challenge Hint 2      | You can't guess random. Maybe you can steal it? |
| Challenge Hint 3      | Do you know all the PHP serialization field types? |

| Admin Facing               | <> |
|----------------------------|---------------------------------------------------------------------|
| Challenge Flag             | WACTF{n0w_Go_buY_soM3_S1ck_sKiNz} |
| Challenge Vuln             | Using the PHP serialization field "R" (reference) you can reference another field, in this case the random code required to get the flag. https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/Insecure%20Deserialization/PHP.md#object-reference |
| Docker Usage Idle          | 5% CPU / 15MB RAM |
| Docker Usage Expected Peak | 10% CPU / 40MB RAM |
---

Challenge PoC

1. python poc.py