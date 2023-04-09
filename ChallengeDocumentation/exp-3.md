| metadata | <> |
|--- | --- |
| Developer Name(s) | Luke H |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Exploit |
| Challenge Tier | 3 |
| Challenge Type | Container + Filedrop |

| Player facing | <> |
|--- | --- |
|Challenge Name | Chat4j |
|Challenge Description | This is heck good chat program. You have a copy of Server.jar which is running at exp-3:8001, and Client.jar. Use the client to interact with the server and hack the thing. Or don't, idc. You'll need java 1.8 for this bad boy.|
|Challenge Hint 1 | We're trying to exploit CVE-2021-44228 here. |
|Challenge Hint 2 | There is some filtering in place, if only you had the code... |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{more_like_flog4j_amirite} |
|Challenge Vuln| Vulnerable version of log4j running through the server program. |
|Docker Usage Idle| 5% / 10MB RAM |
|Docker Usage Peak| 10% / 80MB RAM |
---

Challenge PoC
0. Set the correct IP address in solution/log4j-shell-poc/poc.py:95. This should be the IP of the shellbox or your dev host.
1. Download [JDK 8](https://www.oracle.com/au/java/technologies/javase/javase8-archive-downloads.html) and save it in /solution as "jdk1.8.0_20".
2. Open a netcat listener on the shellbox
3. Run python3 solution/log4j-shell-poc/poc.py
4. Run java -jar Client.jar [host] [port]
5. Paste the string given by the poc in the chat
6. p0p a sh3ll and cat the flag.
