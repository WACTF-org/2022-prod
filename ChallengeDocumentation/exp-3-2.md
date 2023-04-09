| metadata                                  | <> |
|-------------------------------------------|----------------------------------------|
| Developer Name(s)                         | Rhys |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category                        | Exploit |
| Challenge Tier                            | 3-1 |
| Challenge Type                            | Container and Filedrop |

| Player facing         | <>|
|-------------------------------------------|-------------------------------------------------------|
| Challenge Name        | SaaS (Spong as a Service) |
| Challenge Description | Dead memes live forever. `nc exp-3-2 1342`. Hosted: Ubuntu 20.04 |
| Challenge Hint 1      | Seems like you need more input. |
| Challenge Hint 2      | Can you end the loop? Can you end the loop? Can you end the loop? Can you end the loop? |
| Challenge Hint 3      | This custom stack cookie sucks. |

| Admin Facing               | <> |
|----------------------------|---------------------------------------------------------------------|
| Challenge Flag             | WACTF{d3Ad_M3meS_m@kE_g00d_Ch@llenGeZ?} |
| Challenge Vuln             | Stack buffer overflow - overflow max input length on the stack to then overflow ret address and return to secret(). |
| Note | Need to distribute the pwnable binary. |
| Docker Usage Idle          | 0.02% CPU / 14MB RAM |
| Docker Usage Expected Peak | 0.1% CPU / 60MB RAM |
---