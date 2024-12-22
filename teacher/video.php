<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to update the video link.");
}

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Handle video link submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['video_link'])) {
        $videoLink = mysqli_real_escape_string($conn, $_POST['video_link']); // Escape input

        // Extract video ID
        $videoID = '';
        if (strpos($videoLink, 'youtu.be') !== false) {
            $urlParts = explode('/', $videoLink);
            $videoID = explode('?', end($urlParts))[0];
        } elseif (strpos($videoLink, 'youtube.com') !== false) {
            parse_str(parse_url($videoLink, PHP_URL_QUERY), $queryParams);
            $videoID = $queryParams['v'] ?? '';
        }

        if ($videoID) {
            // Update video link in the database for the current user
            $sql = "UPDATE teachers SET video = '$videoID' WHERE id = $userId";
            if (mysqli_query($conn, $sql)) {
                $message = "Video link saved successfully!";
                header("Location: availabilty.php");
            } else {
                $message = "Error updating record: " . mysqli_error($conn);
            }
        } else {
            $message = "Invalid YouTube link.";
        }
    } else {
        $message = "Please enter a video link.";
    }
}

// Retrieve the last saved video for the logged-in user
$sql = "SELECT video FROM teachers WHERE id = $userId";
$result = mysqli_query($conn, $sql);
$video = mysqli_fetch_assoc($result);
$videoEmbedLink = $video && $video['video'] ? "https://www.youtube.com/embed/" . $video['video'] : '';

// Close database connection
mysqli_close($conn);
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
            background-color: #f4f4f6;
            /* Light gray background */
            padding: 10px 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            justify-content: space-between;
        }

        .step {
            display: flex;
            align-items: center;
            margin-right: 20px;
            color: #6c757d;
            /* Gray text color */
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
        }

        .step.active {
            color: #ffffff;
            /* White text for active */
            background-color: #343a40;
            /* Dark background for active */
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
            background-color: #343a40;
            /* Dark background for active step */
            color: white;
            /* White number for active */
        }

        .step.inactive .circle {
            background-color: #e9ecef;
            /* Light background for inactive */
            color: #6c757d;
            /* Gray number for inactive */
        }

        .step span {
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="header" id="header">
        <div class="step inactive">
            <div class="circle">1</div>
            <span>About</span>
        </div>
        <div class="step inactive">
            <div class="circle">2</div>
            <span>Photo</span>
        </div>

        <div class="step inactive">
            <div class="circle">5</div>
            <span>Description</span>
        </div>
        <div class="step active">
            <div class="circle">6</div>
            <span>Video</span>
        </div>
        <div class="step inactive">
            <div class="circle">7</div>
            <span>Availability</span>
        </div>
        <div class="step inactive">
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
    <title>Video Introduction</title>
    <style>
        .outerbody {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 900px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
        }

        .header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .subheader {
            font-size: 1rem;
            margin-bottom: 10px;
            color: #555;
        }

        .video-section {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .video-preview {
            width: 300px;
            height: 200px;
            background-color: #ccc;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1rem;
            color: #555;
            position: relative;
        }

        .video-preview iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 10px;
        }

        .button {
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

        .button:hover {
            background-color: #0056b3;
        }

        .guidelines {
            flex: 1;
        }

        .guidelines-header {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .guidelines ul {
            list-style-type: none;
            padding: 0;
        }

        .guidelines ul li {
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .guidelines ul li::before {
            content: '✔';
            color: green;
            font-size: 1rem;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .modal-content h2 {
            margin-bottom: 20px;
        }

        .modal-content input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .modal-content button {
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-content .save-btn {
            background-color: #007BFF;
            color: white;
        }

        .modal-content .close-btn {
            background-color: #f44336;
            color: white;
        }

        .save-continue {
            margin-top: 20px;
            display: flex;
            justify-content: end;
        }

        .save-continue button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .save-continue button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="outerbody">
        <div class="container">
            <div class="header">Video Introduction</div>
            <div class="subheader">Add a horizontal video of up to 2 minutes</div>
            <div class="video-section">
                <div>
                    <div class="video-preview" id="videoPreview">
                        <iframe id="videoFrame" src="<?= htmlspecialchars($videoEmbedLink) ?>" allowfullscreen></iframe>
                    </div>
                    <button class="button" id="reuploadBtn">Re-Upload</button>
                </div>
                <div class="guidelines">
                    <div class="guidelines-header">Video Requirements</div>
                    <ul>
                        <li>Your video should be between 30 seconds and 2 minutes long</li>
                        <li>Record in horizontal mode and at eye level</li>
                        <li>Use good lighting and a neutral background</li>
                        <li>Use a stable surface so that your video does not appear shaky</li>
                        <li>Make sure your face and eyes are fully visible</li>
                        <li>Highlight your teaching experience and skills</li>
                        <li>Upload your video on YouTube and insert link here.</li>
                    </ul>
                </div>
            </div>
            <div class="save-continue">
                <button id="saveContinueBtn"><a href="availability.php">Save and Continue</a></button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="videoModal">
        <div class="modal-content">
            <h2>Insert Video Link</h2>
            <input type="text" id="videoLinkInput" placeholder="Paste YouTube video link here">
            <button class="save-btn" id="saveVideoLink">Save</button>
            <button class="close-btn" id="closeModal">Close</button>
        </div>
    </div>

    <script>
        // Get elements
        const reuploadBtn = document.getElementById('reuploadBtn');
        const videoModal = document.getElementById('videoModal');
        const closeModal = document.getElementById('closeModal');
        const saveVideoLink = document.getElementById('saveVideoLink');
        const videoLinkInput = document.getElementById('videoLinkInput');
        const videoFrame = document.getElementById('videoFrame');
        const saveContinueBtn = document.getElementById('saveContinueBtn');

        // Open modal
        reuploadBtn.addEventListener('click', () => {
            videoModal.style.display = 'flex';
        });

        // Close modal
        closeModal.addEventListener('click', () => {
            videoModal.style.display = 'none';
        });

        // Save video link
        saveVideoLink.addEventListener('click', () => {
            let videoLink = videoLinkInput.value.trim();

            if (videoLink) {
                let videoID;

                // Handle different YouTube link formats
                if (videoLink.includes('youtu.be')) {
                    // Extract video ID from short URL
                    const urlParts = videoLink.split('/');
                    videoID = urlParts[urlParts.length - 1].split('?')[0]; // Extract ID before query params
                } else if (videoLink.includes('youtube.com')) {
                    // Extract video ID from full URL
                    const urlParams = new URLSearchParams(new URL(videoLink).search);
                    videoID = urlParams.get('v');
                }

                if (videoID) {
                    // Send AJAX request to save video ID in the database
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '', true); // Use current PHP file
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            const response = xhr.responseText;
                            alert(response); // Show success message from PHP
                            // Update iframe with the new embed link
                            const embedLink = `https://www.youtube.com/embed/${videoID}`;
                            videoFrame.src = embedLink;
                            videoModal.style.display = 'none';
                            videoLinkInput.value = '';
                        } else {
                            alert('Error saving video. Please try again.');
                        }
                    };
                    xhr.send(`video_link=${encodeURIComponent(videoLink)}`);
                } else {
                    alert('Please enter a valid YouTube link.');
                }
            } else {
                alert('Please enter a valid YouTube link.');
            }
        });


        // Close modal if clicked outside content
        window.addEventListener('click', (event) => {
            if (event.target === videoModal) {
                videoModal.style.display = 'none';
            }
        });

        // Redirect to the next page


    </script>
</body>

</html>