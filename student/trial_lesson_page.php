<?php
session_start();
if (!isset($_SESSION['referrer'])) {
    $_SESSION['referrer'] = '../student/find_tutors.php';
}


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, show the modal
    $showModal = true;
    
} else {
    // User is logged in, no need to show the modal
    header("Location: modal.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Tutor</title>
</head>
<body>
    
    
    <?php if ($showModal): ?>
        <!-- Modal asking for login -->

        <div id="modal" style="display: block;">
            <div class="modal-content">
                <h2>Please Log In to Proceed</h2>
                <p>You need to log in to book a tutor.</p>
                <a href="../teacher/login.php" id="loginBtn">
                    <button>Login</button>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // If modal is shown, you may need to handle closing it
        const modal = document.getElementById('modal');
        // For now, modal automatically shows when not logged in
    </script>

    <style>
        /* Ensure the modal appears in the center of the page */
        body {
            margin: 0;
            height: 100vh; /* Full screen height */
        }

        #modal {
            position: fixed;
            
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Transparent black background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000; /* Ensure the modal is above other content */
        }

        .modal-content {
            margin: 200px;
            margin-left: 450px;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            max-width: 400px;
            width: 80%;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
<script>
alert('<?php echo $_SESSION['user_id'];?>')

</script>

</body>
</html>
