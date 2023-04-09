| metadata | <> |
|--- | --- |
| Developer Name(s) | Dono |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Web |
| Challenge Tier | 4-2 |
| Challenge Type | Container & Filedrop |

| Player facing | <> |
|--- | --- |
| Challenge Name | BadiFi |
| Challenge Description | Simply find the 0 day and login as root. "www-data:www-data" should get you started |
| Challenge Hint 1 | Ignore the comments |
| Challenge Hint 2 | Become the user you seek  |
| Challenge Hint 3 | Reset their password if you have to |

| Admin Facing | <> |
|--- | --- |
| Challenge Flag | WACTF{0_day_in_BadiFi_achieved} |
| Challenge Vuln | CRLF injection in password change functionality allows resetting of root user's account |
| Docker Usage Idle | 5% CPU / 10MB RAM |
| Docker usage Expected Peak | 10% CPU / 40MB RAM |
---

Challenge PoC

1. python3 -m pip install -r requests
2. pthon3 solution/solve.py -u <http://web-4-2> -p password1

Challenge Outline

* Perform code review of provided source code and scripts
* Identify that the change password functionality is vulnerable to CRLF injection
* Idenitfy that chpasswd is being called under the hood
* Provide a valid input that will cause the root user's password to be overwritten, e.g:

```json
{"old_password":"www-data","new_password":"password1\nroot:password1"}
```
