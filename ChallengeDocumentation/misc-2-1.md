| metadata | <> |
|--- | --- |
| Developer Name(s) | Tarun |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Misc |
| Challenge Tier | 2-1 |
| Challenge Type | FileDrop |

| Player facing | <> |
|--- | --- |
|Challenge Name | Who said logs are your best friend? |
|Challenge Description | Everyone loves bespoke web apps, and security teams loves bespoke log formats even more! This "securely and well written" web app has been popped. They know a webshell was uploaded but it seems to have been deleted and the person who deleted only remembers bits of the file. The file starts with 'p', had a 'php' extension and it was uploaded using the /admin/account.aspx page. Can you identify the source IP?| 
|Challenge Hint 1 | Learn some regex - https://regexcrossword.com/ |
|Challenge Hint 2 | The data is messy but eliminating rows that aren't relevant go a long way |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{182.128.115.208} |
|Challenge Vuln| NA |
---

Challenge PoC
1. Give participant the file to look through
2. Do a bit of light regex `p[a-z]{15}.php` to narrow down (I kept file names the same length to help keep it simple), then filter on URI (only one record will return) and source ip will be in that row.
