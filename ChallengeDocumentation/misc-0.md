| metadata | <> |
|--- | --- |
| Developer Name(s) | Jack |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Misc |
| Challenge Tier | 0 |
| Challenge Type | FileDrop |

| Player facing | <> |
|--- | --- |
|Challenge Name | Strings Is King |
|Challenge Description | Someone told me there may be a flag in this file. Check it out! | 
|Challenge Hint 1 | https://en.wikipedia.org/wiki/Strings_(Unix) |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| `WACTF{C0ngr4ts_y0u_gOt_1t_ee58c4d9a2}` |
|Challenge Vuln| The flag is a string. Thats the vuln |
---

# Challenge PoC
1. Download the file "misc-0".
2. Download the solution/solve.sh script and run it in the same directory to retrieve the flag. The script command is also below:
```bash
strings misc-1 | grep WACTF
```
