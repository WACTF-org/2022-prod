| metadata                                  | <> |
|-------------------------------------------|----------------------------------------|
| Developer Name(s)                         | Rhys |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category                        | Exploit |
| Challenge Tier                            | 3-1 |
| Challenge Type                            | Container and Filedrop |

| Player facing         | <> |
|-----------------------|--------------------------------------------------------------------|
| Challenge Name        | Flag Quest |
| Challenge Description | Can you defeat the dragon and get the flag? `nc exp-3-1 1343` |
| Challenge Hint 1      | The dragon has a lot of health! |
| Challenge Hint 2      | Try kindness. |
| Challenge Hint 3      | WRAPPING the fight up may take some time. |

| Admin Facing               | <> |
|----------------------------|---------------------------------------------------------------------|
| Challenge Flag             | WACTF{t00_h3alThY_f0r_u_2_h4nDl3} |
| Challenge Vuln             | Integer overflow on the dragon's health (signed int16). Heal the dragon enough to wrap it's health into negatives. |
| Docker Usage Idle          | 0% CPU / 7MB RAM |
| Docker Usage Expected Peak | 0.1% CPU / 60MB RAM |
---