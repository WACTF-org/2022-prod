| metadata | <> |
|--- | --- |
| Developer Name(s) | sudosammy |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Exploitation |
| Challenge Tier | 1 |
| Challenge Type | Container + FileDrop |

| Player facing | <> |
|--- | --- |
|Challenge Name | I dropped the backdooor! |
|Challenge Description | So ahh, remember how we dropped a backdoor on that server last week? Well ahhh... my dog ate the instructions for how to use it. :/ We still have the source code that’s running on the system (code in `exp-1.go`), can you figure out how to interact with it again? Access the backdoor on `nc exp-1 1337` |
|Challenge Hint 1 | You will find it useful to run the server locally and in case you didn't know yet, the payload format is as follows (`name[bytes]`) wrapped in base64: `header[5] + uint32[4] + command[x] + md5hash[16])` |
|Challenge Hint 2 | You don't need a reverse shell here. Just `ls` then `cat` your flag. |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{your_a_golang_expert_now_nice1_91127} |
|Challenge Vuln| Some simple golang reverse engineering to determine the format of a payload to send a server to run arbitrary shell commands |
|Docker Usage Idle| 4MB RAM |
|Docker Usage Peak| 5MB RAM |
---

See `main.go` in the solution folder. It simply constructs a valid payload based on the header copy/pasted from the server code + uint32 representation of the shell command length + shell command + md5 hash of the shell command.
