| metadata | <> |
|--- | --- |
| Developer Name(s) | crem |
| Best Contact Slack handle /  | [redacted] |
| Challenge Category | Web |
| Challenge Tier | 1 |
| Challenge Type | Container |

| Player facing | <> |
|--- | --- |
|Challenge Name | x=-y |
|Challenge Description | It is all going downhill from here! Did you not see that math equation |
|Challenge Hint 1 | Developer Tools are not only for Web Development |
|Challenge Hint 2 | How to send request to the Graph? |

| Admin Facing | <> |
|--- | --- |
|Challenge Flag| WACTF{look_at_this_graph_was_made_in_2015_I_am_getting_too_old} |
|Challenge Vuln| GraphQL Introspection Enabled |
| Docker Usage Idle | 5% CPU / 35MB RAM |
| Docker usage Expected Peak | 10% CPU / 80MB RAM |
---


Challenge PoC
1. Run the following curl command:
```
curl 'http://localhost:4000/graphql' -H 'Content-Type: application/json' --data-binary '{"query":"{\n  flag86\n}","variables":{}}'
```