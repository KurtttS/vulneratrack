<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Gemini Chatbot</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .chat-container { width: 400px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow: hidden; }
        .chat-box { flex: 1; padding: 20px; max-height: 400px; overflow-y: auto; border-bottom: 1px solid #eee; }
        .message { margin-bottom: 15px; padding: 10px; border-radius: 5px; max-width: 80%; }
        .user-msg { background: #007bff; color: white; align-self: flex-end; margin-left: auto; }
        .bot-msg { background: #e2e3e5; color: #333; }
        .input-area { display: flex; padding: 10px; background: #fafafa; }
        .input-area input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; outline: none; }
        .input-area button { background: #007bff; color: white; border: none; padding: 10px 15px; margin-left: 5px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-box" id="chatBox">
        <div class="message bot-msg">Hello! How can I help you today?</div>
    </div>
    <div class="input-area">
        <input type="text" id="userInput" placeholder="Type your message here..." onkeypress="handleKeyPress(event)">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<script>
    async function sendMessage() {
        const inputField = document.getElementById('userInput');
        const messageText = inputField.value.trim();
        if (!messageText) return;

        const chatBox = document.getElementById('chatBox');

        // Append User Message to UI
        chatBox.innerHTML += `<div class="message user-msg">${messageText}</div>`;
        inputField.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;

        // Append "Typing..." placeholder
        const typingId = 'typing-' + Date.now();
        chatBox.innerHTML += `<div class="message bot-msg" id="${typingId}"><i>Typing...</i></div>`;
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            // Send payload to PHP Backend
            const response = await fetch('../processes/bot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: messageText })
            });
            
            const data = await response.json();
            document.getElementById(typingId).innerHTML = data.reply || data.error;
        } catch (error) {
            document.getElementById(typingId).innerHTML = "Error connecting to backend.";
        }
        
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }
</script>

</body>
</html>
