| metadata | <> |
|-------------------------------------------|------|
| Developer Name(s)                         | Rhys |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category                        | Web |
| Challenge Tier                            | 2-1 |
| Challenge Type                            | Container|

| Player facing         | <> |
|-------------------------------------------|---------------------------------------------------|
| Challenge Name        | Cookie Forum |
| Challenge Description | A place to share all your best recipes. `http://web-2-1`. Someone seems to be viewing the forum, can you steal their recipe? |
| Challenge Hint 1      | If they won't send you their recipe, force them to post it on the forum. |

| Admin Facing               | <>                                                                  |
|----------------------------|---------------------------------------------------------------------|
| Challenge Flag             | WACTF{100g_suG@r_1_Egg_155G_fL0uR_1_sCriPT_taG} |
| Challenge Vuln             | Vanilla XSS, get the user to fill out the textarea with their cookie and post it to the forum. |
| Docker Usage Idle          | 5% CPU / 140MB RAM |
| Docker Usage Expected Peak | 20% CPU / 180MB RAM |
---

Challenge PoC

1. `python poc.py`