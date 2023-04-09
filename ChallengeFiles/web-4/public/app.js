const app = () => {
    let ELEMENTS = {
        chat: $("#chat")[0],
        chatboxToggle: $("#toggle-chat")[0],
        chatLog: $("#chat-log")[0],
        chatMessage: $("#msg-input")[0],
        chatButton: $("#btn-input")[0],
        askStaffButton: $("#ask-staff")[0],
        askStaffButton2: $("#ask-staff-2")[0],
        askStaffButton3: $("#ask-staff-3")[0],
        toggledChat: false
    };

    function getTimestamp() {
        let date = new Date();
        let current_date = `${date.getFullYear()}-${(date.getMonth() + 1)}-${date.getDate()}`;
        let current_time = `${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
        return `${current_date} ${current_time}`;
    }

    function logMessageToChat(message, timestamp, user) {
        ELEMENTS.chatLog.innerHTML += `<div class="row msg_container base_sent">
            <div class="col-md-10 col-xs-10">
                <div class="messages msg_sent">
                    <p><b>${user ? "Me" : "Helpdesk"}:</b> ${message}</p>
                    <hr/>
                    <p class="datetime"><i>Sent: ${timestamp}</i></p>
                </div>
            </div>
        </div><hr/>`;

        ELEMENTS.chatLog.scrollTop = ELEMENTS.chatLog.scrollHeight;
    }

    function sendMessage(message) {
        fetch("/chat", {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                "message": message,
                "timestamp": getTimestamp()
            })
        })
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            logMessageToChat(data["message"], getTimestamp(), false);
        });
    }

    function doSubmitChat() {
        let message = ELEMENTS.chatMessage.value;
        ELEMENTS.chatMessage.value = "";
        logMessageToChat(message, getTimestamp(), true);
        sendMessage(message);
    }

    function toggleChat() {
        ELEMENTS.toggledChat = !ELEMENTS.toggledChat;
        ELEMENTS.chat.style.display = ELEMENTS.toggledChat ? "block" : "none";
    }

    function init() {
        ELEMENTS.toggledChat = false;
        ELEMENTS.chat.style.display = "none";
        ELEMENTS.chatboxToggle.onclick = () => toggleChat();
        ELEMENTS.askStaffButton.onclick = () => toggleChat();
        ELEMENTS.askStaffButton2.onclick = () => toggleChat();
        ELEMENTS.askStaffButton3.onclick = () => toggleChat();
        ELEMENTS.chatButton.onclick = () => doSubmitChat();
        ELEMENTS.chatMessage.onkeydown = function (e) {
            if (e.keyCode == 13)
                doSubmitChat();
        };

        if (!!document.getElementById("upload-form")) {
            document.getElementById("upload-form").onsubmit = function onSubmit(form) {
                if ( /\.(jpe?g|png|gif)$/i.test(file.files[0].name) === false ) { 
                    document.getElementById("file").value = "";
                    alert("Not a valid file extension.");                     
                    return false;
                }
    
                return true;
            }
        }        
    }

    init();
};

app();