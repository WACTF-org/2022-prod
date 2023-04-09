| metadata | <> |
|--- | --- |
| Developer Name(s) | Chris Elliott |
| Best Contact (Slack handle / Email address) | [redacted] |
| Challenge Category | Misc |
| Challenge Tier | 4 |
| Challenge Type | File Drop |

| Player facing | <> |
|--- | --- |
|Challenge Name | Resume Generator |
|Challenge Description | Oh no! We've been hacked. The attackers have gained access to our sensitive data. See if you can work out how they did it and what data they gained access to. We've zipped up our web server directory with logs for the investigation. | 
|Challenge Hint 1 | Perhaps the input validation is not strict enough? |
|Challenge Hint 2 | https://doc.courtbouillon.org/weasyprint/stable/api_reference.html#weasyprint.document.DocumentMetadata.attachments |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{but_we_performed_input_validation???} |
|Challenge Vuln| Local file inclusion due to insufficient input validation |
|Docker Usage Idle| NA |
|Docker Usage Peak| NA |
---
To solve, the player first needs to workout what the vulnerability is from inspecting the code base. The vulnerability is an injection vulnerability that allows an attacker to inject dangerous HTML tags into the PDF template that includes a file from disk in the PDF's EmbeddedFiles structure.

An attacker has exploited the application to include /proc/self/environ in a PDF attachment.

The player needs to identify which PDF has this file injected. This can be done using some bash wizardry and the pdf-parser tool included with Kali.

For example:

The player needs to identify which PDF has this file injected. This can be done using some bash wizardry and the pdf-parser tool included with Kali.

For example:
`for file in *; do echo $file; pdf-parser $file; done | grep -i embedded -B 100`

This will reveal that the file is cf9b953b-0827-409b-a7aa-84429867608a.pdf and the object that contains the attachment is obj 7.

pdf-parser can then be used to retreive the contents of the attachment:
`pdf-parser cf9b953b-0827-409b-a7aa-84429867608a.pdf --object 7 --raw --filter`

This will reveal the flag which has been stored as an environment variable.

