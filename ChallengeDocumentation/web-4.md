# Metadata
| metadata | <> |
|-------------------------------------------|----------------------------------------|
| Developer Name(s)                         | Rhys and Harsh |
| Best Contact Slack handle / Email address | [redacted] |
| Challenge Category                        | Web |
| Challenge Tier                            | 4 |
| Challenge Type                            | Container |

| Player facing         | <> |
|-------------------------------------------|---------------------------------------------------|
| Challenge Name        | Bad Customer |
| Challenge Description | We've reached public release! `http://web-4` This is sort of a continuation of the Bad Regex challenge. NOTE: Players should use Chrome for this challenge. |
| Challenge Hint 1      | Customer support is actually useful. Send them a link, see what they think. |
| Challenge Hint 2      | Files aren't always what they seem to be. |
| Challenge Hint 3      | My CSP won't let you steal from me! Or will it... |

| Admin Facing               | <>                                                                  |
|----------------------------|---------------------------------------------------------------------|
| Challenge Flag             | WACTF{Th3_cUstOm3R_i5_aLW@yS_RiGh7} |
| Challenge Vuln             | File upload (bypass mimetype), XSS (bypass CSP), send link to customer support bot, get API token, use regex bug for command injection. |
| Docker Usage Idle          | 10% CPU / 50MB RAM |
| Docker Usage Expected Peak | 25% CPU / 180MB RAM |
---

# Comment

A cdn folder has been added to host as the bot does not have internet access but needs certain libraries to bypass CSP.
NOTE: A player should use CHROME if testing their CSP bypass on the live site. This is because Firefox (still) does not support the CSP3 spec.
https://stackoverflow.com/questions/68487357/content-security-policy-should-a-csp-contain-hashes-for-external-scripts

# Challenge PoC

1. python poc.py

# Challenge Outline

A player visits the site. In the HTML source a comment can be found for the `/code` endpoint which shows some of the required endpoints to complete the challenge.

One of the endpoints is the compression endpoint which has a command injection bug. There is some regex setup to prevent command injection, however, upon closer inspection of the regex object, the `g` flag is set.
Because this regex object is global and used multiple times the state of the regex checks are persistent across multiple calls of the compression endpoint, making it possible to bypass the check after multiple submissions of a command injection payload.

To be able to access the command injection bug the player must hava a valid `api_token`. 
To get this token the player must interact with a chatbot which is available on the site. The bot will instruct the player to upload a file to test the compression api. The bot will also mention that the api may have issues
and the player should report any broken links to the support chat so the engineers can inspect it.

The player will have to bypass an extension allow-list by providing an uppercase file extension. This will bypass a content-disposition header that would usually force any visited files to be downloaded. 
Now the player can upload an XSS payload.

The next obsticle the player must bypass is CSP. A CSP is set that only allows specific scripts on the challenge site. These are SHA256 hashed. The intended solutuion is to visit the scripts CDN folder hosted on the site `/cdn/` and develop a CSP bypass using the
available scripts.

Once an XSS payload to steal a cookie has been created, the URL of the uploaded payload can be sent to the bot. It'll then check the uploaded material and send back the `api_token` cookie required to access the api, and ultimately trigger command injection.
From here the player can cat the flag.
