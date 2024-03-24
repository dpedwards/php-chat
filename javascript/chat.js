const form = document.querySelector(".typing-area"),
      incoming_id = form.querySelector(".incoming_id").value,
      inputField = form.querySelector(".input-field"),
      sendBtn = form.querySelector("button"),
      chatBox = document.querySelector(".chat-box");

form.addEventListener('submit', (e) => {
    e.preventDefault();
});

inputField.focus();
inputField.addEventListener('keyup', () => {
    if (inputField.value != "") {
        sendBtn.classList.add("active");
    } else {
        sendBtn.classList.remove("active");
    }
});

sendBtn.addEventListener('click', () => {
    // Using the Fetch API to send data
    fetch('php/insert-chat.php', {
        method: 'POST',
        body: new FormData(form)
    })
    .then(response => response.text())
    .then(() => {
        inputField.value = "";
        scrollToBottom();
    })
    .catch(error => console.error('Error:', error));
});

chatBox.addEventListener('mouseenter', () => {
    chatBox.classList.add("active");
});

chatBox.addEventListener('mouseleave', () => {
    chatBox.classList.remove("active");
});

function fetchChats() {
    fetch('php/get-chat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `incoming_id=${incoming_id}`
    })
    .then(response => response.text())
    .then(data => {
        chatBox.innerHTML = data;
        if (!chatBox.classList.contains("active")) {
            scrollToBottom();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Fetch new chats every 500 milliseconds
setInterval(fetchChats, 500);

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}
