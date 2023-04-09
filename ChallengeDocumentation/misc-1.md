| metadata | <> |
|--- | --- |
| Developer Name(s) | Tarun |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Misc |
| Challenge Tier | 1 |
| Challenge Type | FileDrop |

| Player facing | <> |
|--- | --- |
|Challenge Name | kinda suss |
|Challenge Description | Old mate got a new among us mod and now everyone always know when they're the imposter. I've dumped his autoruns but haven't had time to look through. Can you find if there's anything suss and if so give me the MD5 hash?. Note: Provide flag as WACTF{MD5_HERE} |
|Challenge Hint 1 | Excel/CSVFileView can be your friend if you have to trawl through data you don't know understand, figure out how to filter! |
|Challenge Hint 2 | I wonder what that "signer" column means?? |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{0B5640E1E927CFEA6789BB7EF0DFCB30} |
|Challenge Vuln| Malware kinda suss |
---

Challenge PoC
1. Give participant the file to look through
2. Pretty much all entries are signed so filtering on unsigned should return only one result that has a hash entry.
