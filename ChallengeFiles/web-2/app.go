package main

import (
	"bytes"
	"encoding/base32"
	"encoding/base64"
	"fmt"
	"html/template"
	"image/jpeg"
	"log"
	"net/http"

	"github.com/pquerna/otp"
	"github.com/pquerna/otp/totp"
)

var b32NoPadding = base32.StdEncoding.WithPadding(base32.NoPadding)

var key []byte
var flag = "WACTF{MFA_solves_literally_every_security_problem}"
var logins map[string]Login

type Login struct {
	Username     string
	Password     string
	MFASecret    string
	EmailAddress string
	Name         string
	MFA          *otp.Key
	QRcode       string
}

func home(w http.ResponseWriter, r *http.Request) {
	r.ParseForm() //Parse url parameters passed, then parse the response packet for the POST body (request body)
	// attention: If you do not call ParseForm method, the following data can not be obtained form
	// log.Println(r.Form) // print information on server side.
	// log.Println("path", r.URL.Path)
	// log.Println("scheme", r.URL.Scheme)
	// log.Println(r.Form["url_long"])
	// for k, v := range r.Form {
	// 	log.Println("key:", k)
	// 	log.Println("val:", strings.Join(v, ""))
	// }
	t, _ := template.ParseFiles("home.gtpl")
	t.Execute(w, nil)
	// fmt.Fprintf(w, ``) // write data to response
}

func login(w http.ResponseWriter, r *http.Request) {
	// log.Println("method:", r.Method) //get request method
	if r.Method == "GET" {
		t, _ := template.ParseFiles("login.gtpl")
		t.Execute(w, nil)
	} else {
		r.ParseForm()
		// logic part of log in
		// log.Println("username:", r.Form["username"])
		// log.Println("password:", r.Form["password"])
		// log.Println("otp:", r.Form["otp"])
		if login, ok := logins[r.FormValue("username")]; ok {
			if login.Password == r.FormValue("password") {
				if totp.Validate(r.FormValue("otp"), string(key)) {
					if login.Username != "admin" {
						w.Write([]byte("Sorry - you're not an admin user. No flag for you.\n"))
						return
					}
					w.Write([]byte(fmt.Sprintf("Congratulations: here's flag: %s\n", flag)))
					return
				}
			}
		}

		w.Write([]byte("Login failed.\n"))
	}
}

func register(w http.ResponseWriter, r *http.Request) {
	// log.Println("method:", r.Method) //get request method
	if r.Method == "GET" {
		t, _ := template.ParseFiles("register.gtpl")
		t.Execute(w, nil)
	} else {
		r.ParseForm()
		if r.FormValue("username") == "" || r.FormValue("name") == "" || r.FormValue("email") == "" || r.FormValue("password") == "" {
			w.Write([]byte("Input validation error; you need to type stuff in the boxes maybe.\n"))
			return
		}
		if _, ok := logins[r.FormValue("username")]; ok {
			w.Write([]byte(fmt.Sprintf("Username: '%v' is already registered", r.FormValue("username"))))
			return
		}
		// logic part of log in
		// log.Println("usern ame:", r.Form["username"])
		// log.Println("password:", r.Form["password"])
		// log.Println("name:", r.Form["name"])
		// log.Println("email:", r.Form["email"])
		userMfa := totp.GenerateOpts{Issuer: "WACTF"}
		x := make([]byte, b32NoPadding.DecodedLen(len(key)))
		b32NoPadding.Decode(x, key)
		userMfa.Secret = x
		userMfa.AccountName = r.FormValue("username")
		// copy(userMfa.Secret, key)
		mfa, err := totp.Generate(userMfa)
		// log.Println("The key: ", string(key), string(userMfa.Secret), mfa.Secret())
		if err != nil {
			log.Println(err)
			return
		}
		buf := new(bytes.Buffer)
		qr, err := mfa.Image(200, 200)
		if err != nil {
			panic(err)
		}
		err = jpeg.Encode(buf, qr, nil)
		if err != nil {
			panic(err)
		}
		qrCode := base64.StdEncoding.EncodeToString(buf.Bytes())
		logins[r.FormValue("username")] = Login{Username: r.FormValue("username"), Password: r.FormValue("password"), EmailAddress: r.FormValue("email"), Name: r.FormValue("name"), MFASecret: string(key), MFA: mfa, QRcode: qrCode}
		// log.Println("added ", logins[r.FormValue("username")])
		regSuccess, _ := template.ParseFiles("register_success.gtpl")
		// Error checking elided
		err = regSuccess.Execute(w, logins[r.FormValue("username")])
		if err != nil {
			return
		}
	}
}

func main() {
	logins = make(map[string]Login)
	MfaData := totp.GenerateOpts{Issuer: "WACTF", AccountName: "test"}
	k, _ := totp.Generate(MfaData)
	MfaData.Secret = []byte(k.Secret())
	key = make([]byte, len(MfaData.Secret))
	copy(key, MfaData.Secret)
	logins["admin"] = Login{Password: "password", Username: "admin", MFASecret: string(MfaData.Secret), EmailAddress: "admin@admin.local", Name: "Admin user"} // set default creds for admin account
	// log.Println(string(MfaData.Secret), logins["admin"].MFASecret, string(key))

	http.HandleFunc("/", home) // setting router rule
	http.HandleFunc("/login", login)
	http.HandleFunc("/register", register)
	log.Printf("Listening on: localhost:%d", 9090)

	err := http.ListenAndServe(":9090", nil) // setting listening port
	if err != nil {
		log.Fatal("ListenAndServe: ", err)
	}

}
