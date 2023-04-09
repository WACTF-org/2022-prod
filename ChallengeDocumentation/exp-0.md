| metadata | <> |
|--- | --- |
| Developer Name(s) | sudosammy |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category | Exploitation |
| Challenge Tier | 0 |
| Challenge Type | Container |

| Player facing | <> |
|--- | --- |
|Challenge Name | Hack The Pentagon |
|Challenge Description | A nice easy one to get you started. Just hack The Pentagon. We have uncovered valuable information to get you started. Firstly, we know they are running an SSH server at: `exp-0` on port `22123`. We also know their username is `low_priv` and they use this private key to log in:

```
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDMwCGyekGrdJfHkCzCTeqQrrZ5nzIMzaF8IhujL/B+YZzzMZaO
z/GBCIjjd7jyjwIfeS6oVjVLb2MkeMANtugzTs5SM56MKRot1knDDD+2FOpcwpQ5
odGBr/9V4OeLksySEHZS8tBRe0uc8mLkegnjHymKI48amjTujkhhZfIMkwIDAQAB
AoGAftIOzS6+mEV6caiSoLOEM6OT8NczOAzyFiJGPhjGfHFXtqajW8ffDY5Mw5/3
VX5Q0U/NH4VuM1BF92Q3iQce4PUzp9GM6GAqdgfMbnqCW+bijhmB9Om9cFaSUoWF
rnDkwn82oci6mYxp0/rQp5QDbNQu7ll4mEXos9ObFd5AyQECQQDlcMRIEHbHG8Z3
sbg3dDkNoVV1fqkwqmllrxd/adjgA4PXs4ANOVBVoO5gm7ifPck2pWhHCJk/VB63
/vmTHfsTAkEA5HOz4+toXQM9WP2mpxryoXHmrVpKNVbvf+76VAtUbNN3EuiX715r
tuJfR63KnEZI0YsFnK9cRp8wIq2RnVhYgQJAdm+9go2tIp691m+Q7l86TETpvlsB
rzNocKrvcEANSaXG2wO3qBvb0jfelVQM+3aDdYfwyiowITalHMmrvzuMrQJBAMmh
wU1R8dveRyArSBLHOwz5TeMFBOhNIbelwjW5hjtC7R64QZlYmfNhmzzVjDUf55di
f4KKWARQ7hg9X38jgYECQQDCzfzTr+ElhzVTjvnpx13kU3ygllsZxVbfmflvfj7E
SuPMqnoLICCf2lvc2phNYrVfgowNhdtHhDRx0HoGm2zu
-----END RSA PRIVATE KEY-----
```

We don't know exactly where the intelligence we’re looking for will be on the server though... |

|Challenge Hint 1 | If you are using Linux or WSL `ssh low_priv@exp-0 -p 22123 -i <location of priv_key>` should get you started. |
|Challenge Hint 2 | The flag is in `/the_flag_is_in_this_file.txt` |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{congratz_your_a_sysadmin_now_254360} |
|Challenge Vuln| Simply SSH to the server with a private key and non-standard port and `ls` for the filename and then `cat` the flag. |
|Docker Usage Idle| 18MB RAM |
|Docker Usage Peak| 23MB RAM |
---

1. `ssh low_priv@exp-0 -p 22123 -i privkey.key`
2. `ls` then `cat the_flag_is_in_this_file.txt`
3. ???
4. Profit