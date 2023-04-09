package main

import (
	"bufio"
	"bytes"
	"crypto/md5"
	b64 "encoding/base64"
	"encoding/binary"
	"fmt"
	"io/ioutil"
	"os"
	"os/exec"
	"reflect"
)

var backdoorHeader = [...]byte{115, 97, 109, 109, 121}
var debug = false

func en64(data []byte) string {
	return b64.StdEncoding.EncodeToString([]byte(data))
}

func de64(data string) ([]byte, error) {
	return b64.StdEncoding.DecodeString(data)
}

func banner() string {
	b, err := ioutil.ReadFile("./ascii.txt")
	if err != nil {
		panic(err)
	}
	return string(b)
}

func main() {
	fmt.Println(banner())

	reader := bufio.NewReader(os.Stdin)
	text, _ := reader.ReadString('\n')

	rawInput, err := de64(text)
	if err != nil {
		fmt.Println(err)
		os.Exit(1)
	}

	byteReader := bytes.NewReader(rawInput)
	byteStorage := make([]byte, 100)
	byteReader.Read(byteStorage)

	if !reflect.DeepEqual(backdoorHeader[:], byteStorage[:5]) {
		fmt.Println("Invalid data.") // bail out if header is wrong
		os.Exit(1)
	}

	// strip off header from byteStorage
	byteReader.ReadAt(byteStorage, int64(len(backdoorHeader)))

	// get command length
	cmdLen := binary.BigEndian.Uint32(byteStorage[:4])
	if debug {
		fmt.Println("Length of command: ", cmdLen)
	}

	// extract command
	cmd := byteStorage[4:(4 + cmdLen)]
	if debug {
		fmt.Println("Command: ", string(cmd))
	}

	// extract hash
	hash := byteStorage[(4 + cmdLen):(4 + cmdLen + 16)]
	if debug {
		fmt.Println("Hash: ", hash)
	}

	//check hash & execute command if successful
	md5cmd := md5.Sum(cmd)
	if reflect.DeepEqual(md5cmd[:], hash[:]) {
		out, err := exec.Command("sh", "-c", string(cmd)).Output()
		if err != nil {
			fmt.Println(err)
			os.Exit(1)
		}
		fmt.Println(string(out)) // print output of command
	} else {
		fmt.Println("Error with transmission")
		os.Exit(1)
	}
}
