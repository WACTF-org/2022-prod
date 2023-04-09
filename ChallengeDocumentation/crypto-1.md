| metadata | <> |
|--- | --- |
| Developer Name(s) | Luke |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Crypto |
| Challenge Tier | 1 |
| Challenge Type | Container |

| Player facing | <> |
|--- | --- |
|Challenge Name | Secrets |
|Challenge Description | The thing with secrets is that as soon as you tell someone, they're not longer secret. Find the challenge at [http://crypto-1:8000](http://crypto-1:8000) |
|Challenge Hint 1 | Inspect the sauce |
|Challenge Hint 2 | Something should have been removed in prod! maybe we can decrypt it? |
|Challenge Hint 3 | Decrypt the "challenge" in the commments using the AES.Decrypt function + key + iv |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{yeah that's a crypto} |
|Challenge Vuln| Key in the JavaScript. |
|Docker Usage Idle| 5% CPU / 10MB RAM |
|Docker Usage Peak| 5% CPU / 20MB RAM |
---

Challenge PoC
1. Inspect source
2. read javascript code
3. decrypt the string in the comment using key nad IV in JS.
