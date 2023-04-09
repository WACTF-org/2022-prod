// thx nerd: https://yourbasic.org/golang/http-server-example/ 
package main

import (
    "fmt"
    "net/http"
)

func main() {
    http.HandleFunc("/", HelloServer)
    http.ListenAndServe(":8000", nil)
}

func HelloServer(w http.ResponseWriter, r *http.Request) {
    fmt.Fprintf(w, "<html><head><title>Welcome to WACTF!</title></head><body><center><p style='font-size: 26px;'>Congratulations, if you are reading this under the URL: <a href='http://dns-check'>http://dns-check</a> you have successfully connected the WACTF OpenVPN environment. There is nothing more for you to do now except wait for game day where you'll be able to download your team's production OpenVPN configuration.<br><br>DO NOT USE THIS OPENVPN CONFIGURATION AGAIN!</p></center></body></html>")
}