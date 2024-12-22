<?php
session_start();
include 'Connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $user_id = $data['user_id'];
    $teacher_id = $data['teacher_id'];
    $lesson_date = $data['date'];
    $start_time = $data['time'];
    $duration = $data['duration'];

    // Calculate the end time based on the duration
    $end_time = date('H:i', strtotime("+$duration minutes", strtotime($start_time)));

    // Insert the lesson into the lesson_schedule table
    $sql = "INSERT INTO lesson_schedule (student_id, teacher_id, lesson_date, start_time, end_time, lesson_status, price, type, duration)
            VALUES ('$user_id', '$teacher_id', '$lesson_date', '$start_time', '$end_time', 'scheduled', '0', 'trial', '$duration')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }

    $conn->close();
}
?>
