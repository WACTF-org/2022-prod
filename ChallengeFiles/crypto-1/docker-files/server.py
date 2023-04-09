from http.server import BaseHTTPRequestHandler, HTTPServer
import logging
from Crypto.Cipher import AES
from Crypto.Util import Padding
import base64

class S(BaseHTTPRequestHandler):
    def __init__(self, *args):
        with open("index.html", "rb") as f:
            self.html = f.read()
        with open("aes.js", "rb") as f:
            self.aes = f.read()

        self.cipher = AES.new(b'94drthea3lIaDRGA', AES.MODE_CBC, iv=b"M0gi9V2dIELP3N9e")

        super().__init__(*args)

    def _set_response(self):
        self.send_response(200)
        self.send_header('Content-type', 'text/html')
        self.end_headers()

    def do_GET(self):
        response = b""
        if self.path == "/":
            response = self.html
        elif self.path == "/aes.js":
            response = self.aes
        self._set_response()
        self.wfile.write(response)

    def do_POST(self):
        content_length = int(self.headers['Content-Length'])
        post_data = self.rfile.read(content_length)

        response = "none"
        if "/challenge" in self.path:
            pl = Padding.pad(b">>response:" + post_data + b"<<", AES.block_size)
            response = base64.b64encode(self.cipher.encrypt(pl))

        self._set_response()
        self.wfile.write(response)

def run(server_class=HTTPServer, handler_class=S, port=8000):
    logging.basicConfig(level=logging.INFO)
    server_address = ('', port)
    httpd = server_class(server_address, handler_class)
    print('Starting httpd...\n')
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        pass
    httpd.server_close()
    print('Stopping httpd...\n')

if __name__ == '__main__':
    run()