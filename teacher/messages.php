<?php
include 'Connection.php'; // Ensure your database connection is included
include 'contact_us.php';
include 'Header.php'; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preply</title>
    <!-- Bootstrap CSS for responsive design and easy styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .chat-box {
            max-height: 400px;
            overflow-y: scroll;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 8px;
            margin-top: 10px;
            display: flex;
            flex-direction: column;
        }

        .chat-box div {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
            max-width: 80%;
        }

        .chat-box .sender {
            background-color: #d1f5d3;
            text-align: right;
            margin-left: auto;
        }

        .chat-box .receiver {
            background-color: #cce5ff;
            text-align: left;
            margin-right: auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        #message {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 10px;
            font-size: 14px;
        }

        .send-button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .send-button:hover {
            background-color: #45a049;
        }

        .input-container {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        #receiver_id {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    

    <!-- Receiver ID and Message input section -->
    <div class="input-container">
        <div class="form-group">
            <input type="text" id="receiver_id" class="form-control" placeholder="Receiver ID" />
        </div>
        <div class="form-group">
            <textarea id="message" class="form-control" placeholder="Type your message" rows="4"></textarea>
        </div>
        <button class="send-button" onclick="sendMessage()">Send</button>
    </div>

    <h3 class="mt-4">Messages:</h3>
    <!-- Display chat messages -->
    <div id="chatBox" class="chat-box"></div>
</div>

<script>
    // Function to send a message
    function sendMessage() {
        const receiver_id = document.getElementById('receiver_id').value;
        const message = document.getElementById('message').value;

        if (!receiver_id || !message) {
            alert('Please enter both Receiver ID and message.');
            return;
        }

        fetch('send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `receiver_id=${receiver_id}&message=${encodeURIComponent(message)}`
        })
        .then((response) => response.text())
        .then((data) => {
            if (data === 'Message sent') {
                alert('Message sent successfully!');
                document.getElementById('message').value = ''; // Clear message input
                loadMessages(); // Refresh chat window
            } else {
                alert(data);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

// Function to load messages
function loadMessages() {
    const receiver_id = document.getElementById('receiver_id').value;
    console.log("Receiver ID:", receiver_id); // Debugging line

    if (!receiver_id) {
        return;
    }

    fetch(`fetch_messages.php?receiver_id=${receiver_id}`)
        .then((response) => response.json())
        .then((data) => {
            const chatBox = document.getElementById('chatBox');
            chatBox.innerHTML = ''; // Clear existing messages

            if (data.error) {
                chatBox.innerHTML = `<p>${data.error}</p>`;
                return;
            }

            data.forEach((msg) => {
                const msgDiv = document.createElement('div');
                msgDiv.textContent = `[${msg.timestamp}] ${msg.sender_id}: ${msg.message}`;

                // Determine whether the message is sent by the receiver or sender
                if (msg.sender_id === receiver_id) {
                    msgDiv.classList.add('receiver');
                } else {
                    msgDiv.classList.add('sender');
                }

                chatBox.appendChild(msgDiv);
            });

            // Scroll to the bottom of the chat window
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}


    // Call loadMessages when the page is loaded to show message history
    window.onload = loadMessages;
</script>

<!-- Bootstrap JS and dependencies (Popper and Bootstrap JS for collapse to work) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
