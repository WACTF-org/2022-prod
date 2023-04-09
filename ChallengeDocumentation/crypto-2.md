| metadata | <> |
|--- | --- |
| Developer Name(s) | Swarley + Kronicd |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Crypto |
| Challenge Tier | 1 |
| Challenge Type | Container + Filedrop |

| Player facing | <> |
|--- | --- |
|Challenge Name | Leaky CBC |
|Challenge Description | Our resident cryptographic expert Steve has been tasked with ensuring our secret flag service is secured with encryption in transit. <b> Despite Steve's wishes, the source code for the server has been made available for secure code review to ensure the implementation aligns with best practices. We are confident the codebase will pass the review with flying colours! Actually, we've just been informed that Steve is already clearing his desk and personal belongings. <b> Are you able to review the application for implementation flaws that would lead to sensitive data disclosure from the Flag service? I think Steve left a copy of the application running on his machine at:<b> crypto-2:8888 / crypto-2:9999 <b> The codebase is available at: `crypto-2.zip` |
|Challenge Hint 1 | What mode of encryption is in use? How does the ciphertext get generated? (read the source!) |
|Challenge Hint 2 | How is the IV formed between the environments, and how would that lead to compromise of the cryptosystem? |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{use_a_random_iv_please} |
|Challenge Vuln| In CBC mode encryption the IV is not a secret; however, the cryptosystem has been configured to use the Key as the IV. Inadvertently the ciphertext leaks the key. So review the source code for the app; inspect ciphertext through interacting with the service; use a tool to decrypt using a key, then follow the instructions to forge a valid request against the production server and obtain the flag. |
|Docker Usage Idle| 5% / 10MB RAM |
|Docker Usage Peak| 10% / 20MB RAM |
---

Challenge PoC

1. Run solution.sh
2. This will retrieve the flag
