<?php
// Start the session to retrieve user_id
session_start();

// Check if the user is logged in and user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once 'Connection.php'; // You should have a db_connection.php file to connect to the database

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the price from the form
    $price = $_POST['price'];

    // Validate the price input
    if (is_numeric($price) && $price >= 1) {
        // Get the user_id from session
        $user_id = $_SESSION['user_id'];

        // Update the pricing in the database
        $sql = "UPDATE teachers SET pricing = ? WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("di", $price, $user_id);
            
            // Execute the query
            if ($stmt->execute()) {
                // Success message
                $_SESSION['message'] = "Your lesson price has been successfully updated.";
                header("Location: pending_approval.php"); // Redirect to success page
                exit();
            } else {
                // Error message
                $_SESSION['message'] = "An error occurred while updating your price. Please try again.";
              // Redirect to error page
                
            }
        } else {
            // Error message if statement preparation fails
            $_SESSION['message'] = "Database error. Please try again later.";
            
        }
    } else {
        // Invalid input
        $_SESSION['message'] = "Please enter a valid price.";
       
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Lesson Price</title>
    <style>
        .outerbody {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .container h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .container p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .recommendation {
            font-weight: bold;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .note {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background-color: #f0f8ff;
            color: #1d4ed8;
            padding: 15px;
            border-left: 5px solid #3b82f6;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .note-icon {
            font-size: 20px;
            margin-top: 3px;
        }

        .note-text {
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .button-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .button-group .back-btn {
            background-color: #ccc;
            color: #333;
        }

        .button-group .submit-btn {
            background-color: #9b59b6;
            color: #fff;
        }

        .button-group .submit-btn:hover {
            background-color: #8e44ad;
        }

        .success-message {
            color: #27ae60;
            font-size: 16px;
            margin-top: 20px;
        }

        .error-message {
            color: #e74c3c;
            font-size: 16px;
            margin-top: 20px;
        }
        </style>
</head>
<body>
    <div class="outerbody">
    <div class="container">
        <h1>Set your 50-minute lesson price</h1>
        <p>
            Starting with a competitive price can help attract more students. Once you've aced your first few trial lessons, feel free to adjust this price to meet your goals.
        </p>
        <p class="recommendation">To help you get started, we recommend you set your initial lesson price to $3</p>

        <form action="change_price.php" method="POST">
            <div class="input-group">
                <label for="price">Price in USD only</label>
                <input type="number" id="price" name="price" value="3" min="1" step="1" required>
            </div>

            <div class="note">
                <div class="note-icon">💡</div>
                <div class="note-text">
                    Tutors who follow our price recommendation have a <strong>40% higher chance</strong> of teaching their first trial lesson <strong>within a week of approval</strong>.
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="submit-btn">Complete registration</button>
            </div>
        </form>
    </div>
    </div>
</body>
</html>