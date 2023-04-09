package main

import (
	"bufio"
	"bytes"
	"crypto/aes"
	"crypto/cipher"
	"crypto/rand"
	"encoding/hex"
	"errors"
	"fmt"
	"io"
	"log"
	"net"
	"os"
)

// Constants used throughout the app
var (
	// ErrInvalidBlockSize indicates hash blocksize <= 0.
	ErrInvalidBlockSize = errors.New("invalid blocksize")

	// ErrInvalidPKCS7Data indicates bad input to PKCS7 pad or unpad.
	ErrInvalidPKCS7Data = errors.New("invalid PKCS7 data (empty or not padded)")

	// ErrInvalidPKCS7Padding indicates PKCS7 unpad fails to bad input.
	ErrInvalidPKCS7Padding = errors.New("invalid padding on input")

	blocksize   = 16
	environment = ""
)

// Padding and unpadding functions for PKCS7 - shamelessly stolen from stackoverflow!
func Unpad(src []byte) []byte {
	length := len(src)
	unpadding := int(src[length-1])
	return src[:(length - unpadding)]
}

func pkcs7Pad(b []byte, blocksize int) ([]byte, error) {
	if blocksize <= 0 {
		return nil, ErrInvalidBlockSize
	}
	if b == nil || len(b) == 0 {
		return nil, ErrInvalidPKCS7Data
	}
	n := blocksize - (len(b) % blocksize)
	pb := make([]byte, len(b)+n)
	copy(pb, b)
	copy(pb[len(b):], bytes.Repeat([]byte{byte(n)}, n))
	return pb, nil
}

// CBC Encryption routine
func encryptCBC(key, plaintext []byte, isProd bool) (ciphertext []byte, err error) {
	plaintext, err = pkcs7Pad(plaintext, blocksize)
	if err != nil {
		return nil, err
	}
	block, err := aes.NewCipher(key)
	if err != nil {
		panic(err)
	}

	ciphertext = make([]byte, len(plaintext))
	iv := make([]byte, blocksize)
	// Fixed random IV in dev for testing purposes
	copy(iv, key[:blocksize])
	// Using random IV in prod for security
	if isProd {
		if _, err := io.ReadFull(rand.Reader, iv); err != nil {
			panic(err)
		}
	}
	log.Println("Key: ", hex.EncodeToString(key), "; IV: ", hex.EncodeToString(iv))

	cbc := cipher.NewCBCEncrypter(block, iv)
	cbc.CryptBlocks(ciphertext, plaintext)
	ciphertext = append(iv, ciphertext...)
	outdata := make([]byte, hex.EncodedLen(len(ciphertext)))

	hex.Encode(outdata, ciphertext)
	return outdata, nil
}

// Decryption routine
func decryptCBC(key, ct []byte) (plaintext []byte, err error) {
	var block cipher.Block
	ciphertext := make([]byte, hex.DecodedLen(len(ct)))
	hex.Decode(ciphertext, ct)
	if block, err = aes.NewCipher(key); err != nil {
		return
	}

	if len(ciphertext) < blocksize {
		log.Println("ciphertext too short:", ciphertext)
		return
	}

	iv := ciphertext[:blocksize]
	ciphertext = ciphertext[blocksize:]
	if len(ciphertext)%blocksize != 0 {
		return nil, fmt.Errorf("invalid ct len: %v", len(ciphertext)%blocksize)
	}
	cbc := cipher.NewCBCDecrypter(block, iv)
	cbc.CryptBlocks(ciphertext, ciphertext)

	plaintext = Unpad(ciphertext)

	return
}

// Sender formats messages for delivery via the socket
func Sender(data []byte, conn net.Conn) {
	data = append([]byte(fmt.Sprintf("(%s-ENV)Encrypted Message: ", environment)), data...)
	data = append(data, 0x0a) // add newline
	conn.Write(data)
}

// Server handles socket connections from clients
// Implements business logic
func Server(key []byte, isProd bool, conn net.Conn) {
	defer conn.Close()
	var err error

	pt := []byte("Please request the flag by typing FLAG into the console")
	ct, err := encryptCBC(key, pt, isProd)
	if err != nil {
		log.Println(err)
	}
	Sender(ct, conn)
	for {
		// handle messages from / to client
		message, _ := bufio.NewReader(conn).ReadString('\n')
		if message == "" {
			break
		}
		clientPt, err := decryptCBC(key, []byte(message))
		if err != nil {
			log.Println(err)
			continue
		}

		log.Println("Message Received:", string(clientPt))
		if string(clientPt) == "FLAG" {
			log.Println("Flag request received; Sending flag!")
			flagTxt, err := encryptCBC(key, []byte(os.Getenv(os.Getenv("ENVIRONMENT")+"FLAG")), isProd)
			if err != nil {
				log.Println(err)
				panic(err)
			}
			Sender(flagTxt, conn)
			break
		} else {
			txt, err := encryptCBC(key, []byte("Sorry, request not recognised. Please type FLAG if you want the flag."), isProd)
			if err != nil {
				log.Println(err)
				panic(err)
			}
			Sender(txt, conn)
		}
	}
}

func main() {
	environment = os.Getenv("ENVIRONMENT")
	isProd := environment == "PROD"

	// Use separate keys in dev and prod
	// key, err := hex.DecodeString(os.Getenv(environment + "KEY"))
	key, err := hex.DecodeString(os.Getenv("DEVKEY"))
	if err != nil {
		log.Println(err)
		os.Exit(1)
	}
	log.Println("Startserver", environment)

	// Listen on appropriate ports
	srv, err := net.Listen("tcp", fmt.Sprintf(":%s", os.Getenv(environment+"PORT")))
	if err != nil {
		log.Fatal(err)
	}
	log.Printf("%s Server started on %s\n", environment, os.Getenv(environment+"PORT"))
	// run loop forever (or until ctrl-c)

	for {
		// accept connection
		conn, err := srv.Accept()

		if err != nil {
			continue
		}
		go Server(key, isProd, conn)
	}
}
