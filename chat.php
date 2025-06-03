<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Real-Time Chat</title>
    <style>
        #chat-box { height: 300px; border: 1px solid #ccc; overflow-y: scroll; padding: 10px; margin-top: 10px; }
        .message { margin: 5px 0; }
        #users { float: right; width: 200px; border: 1px solid #ccc; padding: 10px; height: 300px; }
    </style>
</head>
<body>
    <div id="users">
        <h3>Online Users</h3>
        <ul id="online-users"></ul>
    </div>

    <div>
        <h2>Chat</h2>
        <div id="chat-box"></div>
        <form id="chat-form">
            <input type="text" id="message-input" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const usersList = document.getElementById('online-users');
        let lastMessageId = 0;

        // Fetch online users
        async function fetchOnlineUsers() {
            const response = await fetch('online.php');
            const text = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(text, 'text/html');
            usersList.innerHTML = doc.querySelector('#online-users').innerHTML;
        }

        // Fetch new messages
        async function fetchMessages() {
            const response = await fetch(`get_messages.php?lastId=${lastMessageId}`);
            const messages = await response.json();
            messages.forEach(msg => {
                if (msg.id > lastMessageId) {
                    lastMessageId = msg.id;
                    const div = document.createElement('div');
                    div.className = 'message';
                    div.innerHTML = `<strong>${msg.user}</strong> [${msg.time}]: ${msg.message}`;
                    chatBox.appendChild(div);
                }
            });
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Send a new message
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            await fetch('send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `message=${encodeURIComponent(message)}`
            });

            messageInput.value = '';
        });

        // Poll for updates every 2 seconds
        setInterval(() => {
            fetchMessages();
            fetchOnlineUsers();
        }, 2000);

        // Initial load
        fetchMessages();
        fetchOnlineUsers();
    </script>
</body>
</html>