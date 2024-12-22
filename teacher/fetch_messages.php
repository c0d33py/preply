<?php
// fetch_messages.php

$receiver_id = $_GET['receiver_id'];

i
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages for the given receiver_id
$sql = "SELECT sender_id, message, timestamp FROM messages WHERE receiver_id = '$receiver_id' ORDER BY timestamp ASC";
$result = $conn->query($sql);

$messages = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
} else {
    echo json_encode(array('error' => 'No messages found.'));
}

$conn->close();
?>
