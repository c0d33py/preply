<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Header Styles */
        .custom-header {
            background-color: #ffffff; /* White background for clean look */
            padding: 15px 30px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .custom-header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .custom-header .logo img {
            width: 30px; /* Adjust logo size */
            height: 30px;
        }

        .custom-header .logo a {
            font-weight: bold; /* Make "Find Tutors" bold */
            font-size: 1.2rem; /* Make the "Find Tutors" text smaller */
            color: #000; /* Black for logo text */
            text-decoration: none;
        }

        .custom-header .logo a:hover {
            color: #6a0dad; /* Highlight on hover */
        }

        /* Make all icons appear bold using text-shadow */
        .user-actions i {
            font-size: 1.3rem; /* Make icons smaller */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Make icons appear bold */
            color: #000; /* Ensure icons remain black */
        }

        /* Remove default link styles */
        .user-actions a {
            text-decoration: none; /* Remove underline */
            color: inherit; /* Ensure icons are black and don't turn blue */
        }

        /* Make the "72 USD" text smaller */
        .user-actions span {
            font-size: 1rem; /* Make the "72 USD" smaller */
        }

        .user-actions span,
        .user-actions i {
            font-weight: normal; /* Keep the icons and text as normal */
        }

        .user-actions i {
            margin-left: 10px;
            cursor: pointer; /* Make icons look clickable */
        }

        .user-actions img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Make logos bold */
        .user-actions .bi,
        .logo a {
            font-weight: bold; /* Ensure icons and "Find Tutors" are bold */
        }

    </style>
</head>
<body>
    <!-- Header -->
    <div class="custom-header d-flex justify-content-between align-items-center">
        <!-- Logo and "Find Tutors" Link -->
        <div class="logo">
            <img src="logo.png" alt="Logo"> <!-- Replace placeholder with your actual logo -->
            <h4>Preply</h4>
        </div>
        <!-- User Actions -->
        <div class="user-actions d-flex align-items-center gap-4">
            <span><i class="bi bi-wallet"></i> <span style="font-size: 1rem;">72</span> USD</span>
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    English, USD
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">English</a></li>
                    <li><a class="dropdown-item" href="#">Spanish</a></li>
                </ul>
            </div>
            <a href="#"><i class="bi bi-chat-dots"></i></a> <!-- Chat Icon wrapped in a link -->
            <a href="#"><i class="bi bi-question-circle"></i></a> <!-- Help Icon wrapped in a link -->
            <a href="#"><i class="bi bi-bell"></i></a> <!-- Notification Icon wrapped in a link -->
            <a href="#"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAALAAAACUCAMAAAAEVFNMAAAAMFBMVEXk5ueutLfo6uu/xMersbWor7LJzc/f4eK1ur26v8LS1dfP0tSxt7rW2drb3t/Cx8mNRmv9AAAEIUlEQVR4nO2c2a7qOgxAG2dum/T///YkLXChF0om7Gwp6wVt8bCXLGe2mabBYDAYDAaDwWAwGAxeAQBqhVSCqnbzsizO6z9g7RduZEAIET/MNvupV20AryyTgj0jJOPKd6kM3q7i1fbmLFau+zP29p3sI862L2XQSl747mFWHSmDM9e6u7JxvRjD9tV2N2ZLH8aayyRhxuRG7RrRV6PtbGwn+iAnpO9TWtAbZ/lGY02qm5MPN2NOKrzl+gZjRZcUMOf7hpFHOB+nzmcnyNLYlvlSpTEsJQmx40iEtSn1ZYZEWBVmcECS7CqKE4ImxFAR4AB+iKFGlzGL7ls+RRxgrx7A63wF9ta4Yk47QN61VWcEkx5VuGSbdhLG3bTl74P/h8UVrvZlElXYVa0ahzDqqKtb5g7hGTHEUD3mkI9KUD/mGOOYwrXLRgRzmqjc+RwYTOEGGTGEh/CZ9a/NEqgR/nPTGm+w0m2YwqqBMOrBeW6w+UE9cvgGwpi+k16rhRnuOb961AnMzVqLUSdnTN8JXG1OrNjX8IW373fQb+ET35c/C2NfX0LlQd8gX/zEY12VMOa6fKNq7RDoAa47OQvce6obriLANE+LxaudVCS+4EsXD0P0dlv8kIR5q/ZqXDTu0J83ntBrgTHmafkMlMwUpDU0BSUeNC/5/7HkDTxBXh6Y9wCGeu3+iTnddyWPbySMvLQgC9uF717qnJLIEv0F/zOwfJ2QBd369g7Q/DIvBNs6qneOADj+sUhbCO56rNv375Wl2GgLRD8TwmzNGgIq9rDGz9X0Gdw7wc3NauM2wje1uG7bTh7ELiS9M/UvG4DLP3vhiKo7ssGYI38FM8ZyHtLCed1R+9fe3bVEUSGkODf37E1UIgy9Tbke2qiCrVN2vc8MFytH+DpoL5TO4T97ZeWXnp6Tt1w3R5PZYf6KthmyD2ezOfSGNdDKsi9ZcOHMzDYjKscFrVD1iRVLGfRs6h+9ApIp//tsBr200Y2IVfnfGgOozFaer8q/3SfPJTc935x/1soYhlqzZHgxXudfpDJMKvFwXKDM26cy+LbJe6b5K1iDYstLZNNL7nAm/ml4IyGTmwX51+lwp9XbHTQoP0lCNLoaalDfk2zcYEpuUSqcbmyqjVF9o3HtGoLrGyeLuhjj5e/DuGZCru+HKTEufziHBs0PmMbgKXRZef+BbnBwK2MtestrURBaiChqGfVkvmWlutCgurIcmb2jJ0yIndx9ELQoX60htxAPeQvxhswFr7icpxl53fDUGRzJK1yhtmV5BcZoh6JLMhpTKgtBW5Fen6mpVQ+S90CwUKvupB+iWzTutMCkrs/VPxTQisS7oPIi0MaIxCJYkpPcO1I7EXpY5g5SRx35xudO4gZIWyn6IPX3BhTvBJu41kE3pPn2zT/LOjniO2nUxgAAAABJRU5ErkJggg==" alt="User Profile" class="rounded-circle"></a> <!-- Profile Image wrapped in a link -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include 'below_header.php   ';
?>