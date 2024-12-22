<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Navigation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-container {
            background-color: #ffffff;
            border-bottom: 1px solid #eaeaea;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            font-size: 1rem;
            font-weight: 500;
        }

        .nav-links a {
            text-decoration: none;
            color: #4d4d4d;
            position: relative;
        }

        .nav-links a:hover, 
        .nav-links a.active {
            color: #000000;
            font-weight: bold;
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #ff99cc;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }

        .profile-info .status {
            font-size: 1rem;
            color: #000000;
            font-weight: 500;
        }

        .profile-info i {
            font-size: 1.2rem;
            color: #4d4d4d;
        }
    </style>
</head>
<body>
    <?php
        // Get the current page name
        $current_page = basename($_SERVER['PHP_SELF']);
    ?>
    <div class="nav-container">
        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="home.php" class="<?= $current_page == 'home.php' ? 'active' : '' ?>">Home</a>
            <a href="messages.php" class="<?= $current_page == 'messages.php' ? 'active' : '' ?>">Messages</a>
            <a href="lessons.php" class="<?= $current_page == 'lessons.php' ? 'active' : '' ?>">My lessons</a>
        </div>

        <!-- Profile Section -->
        <div class="profile-info">
            <i class="bi bi-three-dots"></i>
            <i class="bi bi-eye"></i>
            <span class="status">Profile is visible</span>
            <i class="bi bi-caret-down-fill"></i>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
