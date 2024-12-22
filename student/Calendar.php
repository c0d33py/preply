<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to book a lesson.");
}

include 'Connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$teacher_id = $_SESSION['selected_teacher_id']; 

// Fetch teacher details
$teacher_query = "SELECT * FROM teachers WHERE id = $teacher_id";
$teacher_result = $conn->query($teacher_query);
$teacher = $teacher_result->fetch_assoc();

// Fetch available time slots for the teacher
$lesson_query = "SELECT * FROM lesson_schedule WHERE teacher_id = $teacher_id AND lesson_date BETWEEN CURDATE() AND CURDATE() + INTERVAL 7 DAY";
$lesson_result = $conn->query($lesson_query);
$booked_slots = [];
while ($row = $lesson_result->fetch_assoc()) {
    $booked_slots[$row['lesson_date']][] = $row['start_time'];
}

$duration = isset($_SESSION['selected_duration']) ? $_SESSION['selected_duration'] : '50'; // Default to 50 mins
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Trial Lesson</title>
    <style>
        body {  
        font-family: Arial, sans-serif;  
        margin: 0;  
        height: 100%;  
        display: flex;  
        justify-content: center;  
        align-items: center;  
        background-color: rgba(0, 0, 0, 0.5);  
        }  

        .modal {  
            background-color: white;  
            border-radius: 12px;  
            width: 400px;  
            padding: 20px;  
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);  
        }  

        .modal-content {  
            text-align: center;  
        }  

        .close {  
            float: right;  
            font-size: 24px;  
            cursor: pointer;  
        }  

        .header {  
            display: flex;  
            align-items: center;  
            margin-bottom: 20px;  
        }  

        .tutor-image {  
            width: 50px;  
            height: 50px;  
            border-radius: 50%;  
            margin-right: 10px;  
        }  

        .duration-selection {  
            margin: 20px 0;  
        }  
        .check-days{
            border: 1px solid #ccc;  
            background-color: white;  
            padding: 10px 15px;  
            margin: 5px;  
            border-radius: 5px;  
            cursor: pointer;  
            transition: background 0.3s, border 0.3s;
        }
       


        .option-group {
            display: grid;
            grid-template-columns: repeat(3, minmax(20px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }
        .option {
            padding: 10px 20px;
            border: 2px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .option.selected {
            border-color: black;
            background-color: #f0f0f0;
        }




        .continue-button {  
            background-color: #ff4a4a;  
            color: white;  
            border: none;  
            padding: 12px 20px;  
            border-radius: 5px;  
            cursor: pointer;  
            width: 100%;  
            transition: background 0.3s;  
        }  

        .continue-button:hover {  
            background-color: #e63939;  
        }


                
        .alert {  
            display: none; /* Initially hidden */  
            background-color: #4CAF50; /* Green */  
            color: white;  
            padding: 20px;  
            position: fixed;  
            top: 20px;  
            left: 50%;  
            transform: translateX(-50%);  
            border-radius: 5px;  
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);  
            z-index: 1000;  
            transition: opacity 0.3s ease;  
        }  

        .close-btn {  
            color: white;  
            float: right;  
            font-size: 20px;  
            cursor: pointer;  
        }  

    </style>
</head>
<body>

    <div class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="header">
                <img src="images/<?php echo $teacher['photo']; ?>" alt="Tutor" class="tutor-image">
                <h2>Book a trial lesson with <?php echo $teacher['first_name'] . ' ' . $teacher['last_name']; ?></h2>
            </div>

            <!-- Duration Selection (25 min or 50 min) -->
            <div class="duration-selection" id="group1">
                <button class="option <?php echo $duration === '25' ? 'selected' : ''; ?>" onclick="selectDuration(25)">25 mins</button>
                <button class="option <?php echo $duration === '50' ? 'selected' : ''; ?>" onclick="selectDuration(50)">50 mins</button>
            </div>

            <hr>

            <!-- Calendar and Day Selection -->
            <div class="calendar">
                <h3>Select a Date</h3>
                <div class="check-days" id="group2">
                    <?php
                    $days_of_week = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    foreach ($days_of_week as $day) {
                        $date = date('Y-m-d', strtotime($day . ' this week')); 
                        $day_slots = isset($booked_slots[$date]) ? $booked_slots[$date] : [];
                        if (empty($day_slots)) {
                            echo "<div class='option' data-date='$date' onclick='selectDate(\"$date\")'>$day</div>";
                        } else {
                            echo "<div class='option disabled' style='background-color: #ccc;' onclick='alert(\"No slots available on $day.\")'>$day</div>";
                        }
                    }
                    ?>
                </div>
                <hr>
            </div>

            <div id="time-selection">
                <!-- Available time slots will be dynamically loaded here -->
            </div>

            <button class="continue-button" onclick="bookLesson()">Continue</button>
        </div>
    </div>

    <script>
        let selectedDate = '';
        let selectedTime = '';
        let duration = '<?php echo $duration; ?>'; // Get the selected duration

        function selectDuration(selectedDuration) {
            duration = selectedDuration;
            document.querySelectorAll('#group1 .option').forEach(button => {
                button.classList.remove('selected');
            });
            document.querySelector(`button[onclick="selectDuration(${selectedDuration})"]`).classList.add('selected');
        }

        function selectDate(date) {
            selectedDate = date;
            document.querySelectorAll('#group2 .option').forEach(button => {
                button.style.backgroundColor = (button.getAttribute('data-date') === date) ? '#ddd' : '';
            });
            fetchAvailableSlots(date);
        }

        function fetchAvailableSlots(date) {
            const bookedSlots = <?php echo json_encode($booked_slots); ?>;
            const teacherSchedule = <?php echo json_encode([
                'monday_start_time' => $teacher['monday_start_time'],
                'monday_end_time' => $teacher['monday_end_time'],
                // Add for each day, or make a dynamic fetch
            ]); ?>;
            const slotContainer = document.getElementById('time-selection');
            slotContainer.innerHTML = '';

            let availableSlots = [];
            if (bookedSlots[date]) {
                availableSlots = bookedSlots[date];
            }

            if (availableSlots.length === 0) {
                // Populate available slots dynamically, this would need adjustment based on the teacher's availability
                for (let time = teacherSchedule['monday_start_time']; time <= teacherSchedule['monday_end_time']; time++) {
                    if (!availableSlots.includes(time)) {
                        let button = document.createElement('button');
                        button.classList.add('option');
                        button.textContent = time;
                        button.onclick = () => selectTime(time);
                        slotContainer.appendChild(button);
                    }
                }
            }
        }

        function selectTime(time) {
            selectedTime = time;
            document.querySelectorAll('#time-selection .option').forEach(button => {
                button.style.backgroundColor = (button.textContent === time) ? '#ddd' : '';
            });
        }

        function bookLesson() {
            if (!selectedDate || !selectedTime) {
                alert('Please select both a date and time.');
                return;
            }

            // Add lesson to the schedule
            fetch('book_lesson.php', {
                method: 'POST',
                body: JSON.stringify({
                    user_id: <?php echo $_SESSION['user_id']; ?>,
                    teacher_id: <?php echo $teacher_id; ?>,
                    date: selectedDate,
                    time: selectedTime,
                    duration: duration
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Lesson booked successfully!');
                      window.location.href = 'find_tutor.php'; // Redirect after booking
                  } else {
                      alert('Failed to book lesson!');
                  }
              });
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
