<!-- below_header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preply</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General navbar styling */
        .navbar {
            background-color: #FFFFFF;
            padding: 10px 20px;
            border-top: 1px solid #4d3346;
            border-bottom: 1px solid #4d3346;
        }

        .nav-link {
            color: #4d3346;
            font-weight: 500;
            display: inline-block;
            position: relative;
            padding: 10px 20px;
            text-decoration: none;
        }

        .nav-link:hover {
            color: #000000;
            font-weight: bold;
        }

        .nav-link.active {
            font-weight: bold;
            color: #000000;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #ff99cc;
        }

        .nav-item:hover {
            background-color: #D1D1D1;
            border-radius: 5px;
        }

        .dots-menu,
        .profile-btn {
            cursor: pointer;
        }

        /* Stop dropdown from interfering */
        .dropdown-menu {
            pointer-events: auto;
        }
    </style>
</head>
<body>
    <?php
        $current_page = basename($_SERVER['PHP_SELF']);
    ?>
    <nav class="navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Left Section -->
            <ul class="nav d-flex flex-row justify-content-evenly w-75">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'teacher_dashboard.php' ? 'active' : '' ?>" href="teacher_dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'messages.php' ? 'active' : '' ?>" href="messages.php">Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'lessons.php' ? 'active' : '' ?>" href="lessons.php">My Lessons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'classroom.php' ? 'active' : '' ?>" href="classroom.php">Classroom</a>
                </li>
            </ul>

            <!-- Right Section -->
            <div class="d-flex align-items-center">
                <!-- Dots Menu -->
                <div class="dropdown">
                    <button class="btn dots-menu" type="button" id="menuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="menuDropdown">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Help</a></li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown ms-3">
                    <button class="btn profile-btn" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-eye"></i>
                        <span class="me-2">Profile is visible</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2">
                            <span>Students can see your profile in the search results</span>
                        </li>
                        <li>
                            <button class="btn btn-primary w-100 mb-2">Hide Profile</button>
                        </li>
                        <li>
                            <button class="btn btn-outline-dark w-100">
                                <i class="bi bi-eye me-1"></i> Preview Profile
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        // Prevent dropdown from conflicting with collapse
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.addEventListener('click', function (event) {
                event.stopPropagation();
            });
        });
    </script>
</body>
</html>
