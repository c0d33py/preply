<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preply</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Floating button styles */
        .floating-btn {
            position: fixed;
            right: 20px;
            bottom: 20px;
            background-color: #6C3D66; /* Dark Purple */
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Menu container */
        .contact-menu {
            position: fixed;
            right: 20px;
            bottom: 100px;
            background-color: white;
           
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            display: none;
            border-radius: 5px;
            z-index: 999;
        }

        /* Chat interface styles */
        .chat-container {
            position: fixed;
            right: 20px;
            bottom: 80px;
            display: block;
            background-color: white;
            width: 350px;
            height: 350px; /* Reduced height */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            display: none;
            z-index: 1050;
            flex-direction: column;
        }

        .chat-header {
            background-color: #6C3D66;
            color: white;
            padding: 10px;
            font-size: 18px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between; /* Aligns items at opposite ends */
            align-items: center;
        }

        .chat-header span {
            margin-left: 10px; /* Adds spacing for the header text */
        }

        .message {
        max-width: 70%;
        padding: 8px 12px;
        margin: 8px 10px;
        border-radius: 15px;
        display: inline-block;
        word-wrap: break-word;
    }

    .user-message {
        background-color: #9B59B6; /* Purple for user */
        color: white;
        align-self: flex-end;
        border-top-right-radius: 0;
    }

    .assistant-message {
        background-color: #F3F3F3; /* Light gray for assistant */
        color: black;
        align-self: flex-start;
        border-top-left-radius: 0;
    }

    .chat-messages {
        flex-grow: 1;
        padding: 10px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 5px; /* Space between messages */
    }

        .send-btn {
            background-color: #9B59B6; /* Purple */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 5px 10px; /* Adjusted padding */
        }
        .chat-input-container {
    display: flex;
    align-items: center; /* Align items vertically */
    gap: 10px; /* Add spacing between the text area and button */
    padding: 10px;
}

        .close-btn {
            background-color: #C084B1; /* Soft Purple */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 5px 10px;
        }
    </style>
</head>
<body>

    <!-- Floating button with Question mark icon -->
    <button class="floating-btn" id="contactBtn">
        <i class="bi bi-question"></i> <!-- Question mark icon -->
    </button>

    <!-- Contact menu with "Contact Us" option -->
    <div class="contact-menu" id="contactMenu">
        <button class="btn btn-primary w-100" id="contactUsBtn">Contact Us</button>
    </div>

    <!-- Chat Interface -->
    <div class="chat-container" id="chatContainer">
        <div class="chat-header">
            <span>Chat with Us</span> <!-- Left-aligned -->
            <button class="close-btn" id="closeChatBtn">X</button> <!-- Right-aligned -->
        </div>
        <div class="chat-messages" id="chatMessages">
            <!-- Messages will appear here -->
        </div>
        <div class="chat-input-container">
            <textarea id="chatInput" class="chat-input" placeholder="Type your message..." rows="1"></textarea>
            <button class="send-btn" id="sendBtn">Send</button>
        </div>
    </div>

    <!-- Bootstrap JS and Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const contactBtn = document.getElementById('contactBtn');
        const contactMenu = document.getElementById('contactMenu');
        const contactUsBtn = document.getElementById('contactUsBtn');
        const chatContainer = document.getElementById('chatContainer');
        const closeChatBtn = document.getElementById('closeChatBtn');
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');

        let isFirstMessage = true; // Track first message

        // Toggle Contact Menu visibility
        contactBtn.addEventListener('click', () => {
            contactMenu.style.display = contactMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Open Chat Interface
        contactUsBtn.addEventListener('click', () => {
            contactMenu.style.display = 'none';
            chatContainer.style.display = 'flex';
        });

        // Close Chat Interface
        closeChatBtn.addEventListener('click', () => {
            chatContainer.style.display = 'none';
        });

        // Send a message
        function sendMessage() {
    const message = chatInput.value.trim();
    if (message) {
        // User message
        const userMessage = document.createElement('div');
        userMessage.className = 'message user-message';
        userMessage.textContent = message;
        chatMessages.appendChild(userMessage);

        chatInput.value = ''; // Clear input field
        chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll to the latest message

        // Auto-generate assistant's message only for the first user message
        if (isFirstMessage) {
            setTimeout(() => {
                const assistantMessage = document.createElement('div');
                assistantMessage.className = 'message assistant-message';
                assistantMessage.textContent = "Thank you! We'll get back to you soon.";
                chatMessages.appendChild(assistantMessage);

                chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll to the latest message
            }, 1000);
            isFirstMessage = false; // Disable further auto-responses
        }
    }
}


        // Add events for sending messages
        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>

</body>
</html>
