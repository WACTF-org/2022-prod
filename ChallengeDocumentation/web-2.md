| metadata | <> |
|--- | --- |
| Developer Name(s) | Swarley and Kronicd |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Web |
| Challenge Tier | 2 |
| Challenge Type | Container |

| Player facing | <> |
|--- | --- |
|Challenge Name | Use MFA |
|Challenge Description | The admin user for this website has MFA configured. Fortunately for them because their password is trivial. Will MFA protect the admin user from account compromise? http://web-2:9090 |
|Challenge Hint 1 | How are MFA tokens generated per use? |
|Challenge Hint 2 | The password for the admin user account is exceedingly weak; in fact it's leaked in this hint. |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{MFA_solves_literally_every_security_problem} |
|Challenge Vuln| MFA uses a global seed/key for all generated tokens; users register to the app, should notice that generated tokens are identical. Use a self registered account + basic brute force of a trivial password to compromise admin user. |
|Docker Usage Idle| 10B RAM |
|Docker Usage Peak| 40MB RAM |
---

Challenge PoC
1. Click /register
2. spam all the inputs
3. register MFA code into authenticator app using qrcode (or zbarimg the thing and dump the key using `oathtool -b --totp <SECRET>`
4. login to /login using admin/password + mfa token
5. Use intruder/ffuf to automate bruteforce of password