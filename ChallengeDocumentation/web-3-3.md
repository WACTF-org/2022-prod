| metadata                                  | <> |
|-------------------------------------------|----------------------------------------|
| Developer Name(s)                         | Rhys |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category                        | Web |
| Challenge Tier                            | 3-3 |
| Challenge Type                            | Container |

| Player facing         | <>|
|-------------------------------------------|-------------------------------------------------------|
| Challenge Name        | Bad Regex |
| Challenge Description | We created a new API. Gzip anything! I'm sure there are no bugs. `http://web-3-3` |
| Challenge Hint 1      | Sanitization is hard. |
| Challenge Hint 2      | Give it a couple shots. |
| Challenge Hint 3      | Irregular expression. |

| Admin Facing               | <> |
|----------------------------|---------------------------------------------------------------------|
| Challenge Flag             | WACTF{b@d_r3GeX_Nev3R_f0RgEtS} |
| Challenge Vuln             | Regular expression using the 'g' global flag keeps track of the last match that occurred, eventually breaking the match. |
| Docker Usage Idle          | 5% CPU / 20MB RAM |
| Docker Usage Expected Peak | 10% CPU / 40MB RAM |
---

Challenge PoC

1. python poc.py

Challenge Outline

1. discover `/code` endpoint from html comment
2. observe command injection potential
3. obsere use of shared regexp object, with global flag
4. exploit command injection by submitting enough payloads to flood global match
5. read flag.txt