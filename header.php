<!DOCTYPE html>
<?php require_once 'config.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preply</title>
    <link rel="stylesheet" href="<?php echo base_url ?>assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo a {
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>

<body>
    <?php session_start(); ?>
    <header class="d-flex justify-content-between align-items-center mb-4">
        <div class="logo">
            <a href="../index.php">Preply</a>
        </div>
        <nav class="d-flex align-items-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Logged in user navigation -->
                <a class="nav-link" href="../pages/add_lesson.php">Add New Lesson</a>
                <a class="nav-link" href="../pages/teacher_dashboard.php">Dashboard</a>
                <a class="nav-link" href="../pages/update_profile.php">Profile</a>
                <a class="btn btn-log-in" href="../pages/logout.php">Log Out
                    <hr>
                    <?php echo $_SESSION['user_role']; ?>
                </a>
            <?php else: ?>
                <!-- Guest navigation -->
                <a class="nav-link" href="student/find_tutors.php">Find tutors</a>
                <a class="nav-link" href="../pages/register.php">Become a tutor</a>
                <a class="btn btn-log-in" href="../pages/login.php">Log In</a>
            <?php endif; ?>
        </nav>
    </header>