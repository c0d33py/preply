
<?php
session_start();
require 'Connection.php'; // Include your database connection file

// Ensure the user is logged in (checking session for user_id)
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$user_id = $_SESSION['user_id']; // Get user_id from session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the form
    $timezone = $_POST['timezone'];
    $monday_from = $_POST['monday_from'];
    $monday_to = $_POST['monday_to'];
    $tuesday_from = $_POST['tuesday_from'];
    $tuesday_to = $_POST['tuesday_to'];
    $wednesday_from = $_POST['wednesday_from'];
    $wednesday_to = $_POST['wednesday_to'];
    $thursday_from = $_POST['thursday_from'];
    $thursday_to = $_POST['thursday_to'];
    $friday_from = $_POST['friday_from'];
    $friday_to = $_POST['friday_to'];
    $saturday_from = $_POST['saturday_from'];
    $saturday_to = $_POST['saturday_to'];
    $sunday_from = $_POST['sunday_from'];
    $sunday_to = $_POST['sunday_to'];
// Ensure you replace with your actual DB details

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the UPDATE query
    $sql = "UPDATE teachers SET
            timezone = ?, monday_start_time = ?, monday_end_time = ?, 
            tuesday_start_time = ?, tuesday_end_time = ?, wednesday_start_time = ?, 
            wednesday_end_time = ?, thursday_start_time = ?, thursday_end_time = ?, 
            friday_start_time = ?, friday_end_time = ?, saturday_start_time = ?, 
            saturday_end_time = ?, sunday_start_time = ?, sunday_end_time = ? 
            WHERE user_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Error preparing the statement: ' . $conn->error);
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param('sssssssssssssssi', $timezone, $monday_from, $monday_to, $tuesday_from, 
                      $tuesday_to, $wednesday_from, $wednesday_to, $thursday_from, $thursday_to, 
                      $friday_from, $friday_to, $saturday_from, $saturday_to, $sunday_from, 
                      $sunday_to, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Availability updated successfully!";
    } else {
        echo "Error updating availability: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Header Steps</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .header {
            display: flex;
            align-items: center;
            background-color: #f4f4f6; /* Light gray background */
            padding: 10px 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            justify-content: space-between;
        }

        .step {
            display: flex;
            align-items: center;
            margin-right: 20px;
            color: #6c757d; /* Gray text color */
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
        }

        .step.active {
            color: #ffffff; /* White text for active */
            background-color: #343a40; /* Dark background for active */
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .step .circle {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            text-align: center;
            margin-right: 10px;
            font-size: 0.8rem;
        }

        .step.active .circle {
            background-color: #343a40; /* Dark background for active step */
            color: white; /* White number for active */
        }

        .step.inactive .circle {
            background-color: #e9ecef; /* Light background for inactive */
            color: #6c757d; /* Gray number for inactive */
        }

        .step span {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header" id="header">
        <div class="step inactive" >
            <div class="circle">1</div>
            <span>About</span>
        </div>
        <div class="step inactive" >
            <div class="circle">2</div>
            <span>Photo</span>
        </div>

        <div class="step inactive" >
            <div class="circle">5</div>
            <span>Description</span>
        </div>
        <div class="step inactive" >
            <div class="circle">6</div>
            <span>Video</span>
        </div>
        <div class="step active">
            <div class="circle">7</div>
            <span>Availability</span>
        </div>
        <div class="step inactive" >
            <div class="circle">8</div>
            <span>Pricing</span>
        </div>
    </div>


</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Availability Settings</title>
    <style>
        /* styles.css */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
            color: #333;
        }
        button {
            display: inline-block;
            margin-top: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .availability-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #6c3d66;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        label {
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d8a7ca;
            border-radius: 4px;
            background: #f9f9f9;
            font-size: 14px;
            color: #333;
        }

        .availability-section {
            margin-top: 30px;
        }

        .info-box {
            background: #d8a7ca;
            color: #6c3d66;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 5px 0;
        }

        .day-availability {
            margin-top: 20px;
        }

        .day {
            margin-bottom: 20px;
        }

        .day label {
            font-weight: bold;
            margin-left: 10px;
        }

        .time-slot {
            display: flex;
            align-items: center;
            margin-top: 10px;
            gap: 10px;
        }

        .add-timeslot {
            color: #6c3d66;
            text-decoration: underline;
            font-size: 14px;
            margin-left: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="availability-container">
        <h1>Availability</h1>

        <!-- Form for Availability Settings -->
        <form action="change_price.php" method="POST">

            <!-- Timezone Section -->
            <div class="timezone-section">
                <h2>Set your timezone</h2>
                <p>A correct timezone is essential to coordinate lessons with international students</p>
                <label for="timezone">Choose your timezone</label>
                <select id="timezone" name="timezone" required>
                <option value="gmt-12">00:00 (GMT-12) - Baker Island, USA</option>
<option value="gmt-11">01:00 (GMT-11) - American Samoa, USA</option>
<option value="gmt-10">02:00 (GMT-10) - Hawaii-Aleutian Standard Time, USA</option>
<option value="gmt-9">03:00 (GMT-9) - Alaska Standard Time, USA</option>
<option value="gmt-8">04:00 (GMT-8) - Pacific Standard Time, USA</option>
<option value="gmt-7">05:00 (GMT-7) - Mountain Standard Time, USA</option>
<option value="gmt-6">06:00 (GMT-6) - Central Standard Time, USA</option>
<option value="gmt-5">07:00 (GMT-5) - Eastern Standard Time, USA / Colombia</option>
<option value="gmt-4">08:00 (GMT-4) - Atlantic Standard Time, Canada / Venezuela</option>
<option value="gmt-3">09:00 (GMT-3) - Argentina / Brazil (Brasilia Time)</option>
<option value="gmt-2">10:00 (GMT-2) - Mid-Atlantic Time (Azores, Cape Verde)</option>
<option value="gmt-1">11:00 (GMT-1) - Azores Time, Portugal</option>
<option value="gmt0">12:00 (GMT+0) - Greenwich Mean Time (UK, Ireland, Portugal)</option>
<option value="gmt+1">13:00 (GMT+1) - Central European Time (CET) - Spain, France, Germany</option>
<option value="gmt+2">14:00 (GMT+2) - Eastern European Time (EET) - Greece, Cyprus</option>
<option value="gmt+3">15:00 (GMT+3) - Moscow Time (Russia), Saudi Arabia</option>
<option value="gmt+4">16:00 (GMT+4) - Azerbaijan Time, UAE, Armenia</option>
<option value="gmt+5">17:00 (GMT+5) - Pakistan Standard Time (Karachi), Uzbekistan</option>
<option value="gmt+6">18:00 (GMT+6) - Bangladesh Standard Time, Kyrgyzstan</option>
<option value="gmt+7">19:00 (GMT+7) - Indochina Time (Thailand, Vietnam, Cambodia)</option>
<option value="gmt+8">20:00 (GMT+8) - China Standard Time (China, Singapore, Malaysia)</option>
<option value="gmt+9">21:00 (GMT+9) - Japan Standard Time (Japan), Korea Standard Time (South Korea)</option>
<option value="gmt+10">22:00 (GMT+10) - Australian Eastern Standard Time (AEST)</option>
<option value="gmt+11">23:00 (GMT+11) - Solomon Islands Time, New Caledonia</option>
<option value="gmt+12">00:00 (GMT+12) - Fiji Standard Time, Marshall Islands</option>

                    <!-- Add other timezone options here -->
                </select>
            </div>

            <!-- Availability Section -->
            <div class="availability-section">
                <h2>Set your availability</h2>
                <p>Availability shows your potential working hours. Students can book lessons at these times.</p>
                <div class="info-box">
                    <p><strong>Add popular hours to get more students</strong></p>
                    <p>Most students book lessons between 6:00 and 9:00 (popular hours). Add time slots during these hours to triple your chances of getting booked.</p>
                </div>

                <!-- Day-specific Availability -->
                <div class="day-availability">
                    <!-- Days of the Week -->
                    <div class="day">
                        <input type="checkbox" id="monday" name="availability[monday]" checked>
                        <label for="monday">Monday</label>
                        <div class="time-slot">
                            <label for="monday-from">From</label>
                            <select id="monday-from" name="monday_from">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>

                            </select>
                            <label for="monday-to">To</label>
                            <select id="monday-to" name="monday_to">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                        </div>
                    </div>

                    <div class="day">
                        <input type="checkbox" id="tuesday" name="availability[tuesday]" checked>
                        <label for="tuesday">Tuesday</label>
                        <div class="time-slot">
                            <label for="tuesday-from">From</label>
                            <select id="tuesday-from" name="tuesday_from">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                            <label for="tuesday-to">To</label>
                            <select id="tuesday-to" name="tuesday_to">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                        </div>
                    </div>

                    <div class="day">
                        <input type="checkbox" id="wednesday" name="availability[wednesday]" checked>
                        <label for="wednesday">Wednesday</label>
                        <div class="time-slot">
                            <label for="wednesday-from">From</label>
                            <select id="wednesday-from" name="wednesday_from">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                            <label for="wednesday-to">To</label>
                            <select id="wednesday-to" name="wednesday_to">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                        </div>
                    </div>

                    <div class="day">
                        <input type="checkbox" id="thursday" name="availability[thursday]" checked>
                        <label for="thursday">Thursday</label>
                        <div class="time-slot">
                            <label for="thursday-from">From</label>
                            <select id="thursday-from" name="thursday_from">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                            <label for="thursday-to">To</label>
                            <select id="thursday-to" name="thursday_to">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                        </div>
                    </div>

                    <div class="day">
                        <input type="checkbox" id="friday" name="availability[friday]" checked>
                        <label for="friday">Friday</label>
                        <div class="time-slot">
                            <label for="friday-from">From</label>
                            <select id="friday-from" name="friday_from">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                            <label for="friday-to">To</label>
                            <select id="friday-to" name="friday_to">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                        </div>
                    </div>

                    <div class="day">
                        <input type="checkbox" id="saturday" name="availability[saturday]" checked>
                        <label for="saturday">Saturday</label>
                        <div class="time-slot">
                            <label for="saturday-from">From</label>
                            <select id="saturday-from" name="saturday_from">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                            <label for="saturday-to">To</label>
                            <select id="saturday-to" name="saturday_to">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                        </div>
                    </div>

                    <div class="day">
                        <input type="checkbox" id="sunday" name="availability[sunday]" checked>
                        <label for="sunday">Sunday</label>
                        <div class="time-slot">
                            <label for="sunday-from">From</label>
                            <select id="sunday-from" name="sunday_from">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                            <label for="sunday-to">To</label>
                            <select id="sunday-to" name="sunday_to">
                            <option value="00:00">00:00</option>
<option value="01:00">01:00</option>
<option value="02:00">02:00</option>
<option value="03:00">03:00</option>
<option value="04:00">04:00</option>
<option value="05:00">05:00</option>
<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>
<option value="19:00">19:00</option>
<option value="20:00">20:00</option>
<option value="21:00">21:00</option>
<option value="22:00">22:00</option>
<option value="23:00">23:00</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit">Save Availability</button>
        </form>
    </div>
</body>
</html>
