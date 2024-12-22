<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trial Duration Modal</title>
  <style>
    /* Modal Styles */
    .modal {
      display: block; /* Show by default */
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
      padding-top: 60px;
    }

    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 500px;
      text-align: center;
      position: relative;
    }

    .close-btn {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      position: absolute;
      top: 10px;
      right: 25px;
      cursor: pointer;
    }

    .close-btn:hover,
    .close-btn:focus {
      color: black;
      text-decoration: none;
    }

    .options {
      margin: 20px 0;
    }

    .trial-btn {
      display: block;
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      font-size: 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    .trial-btn:hover {
      background-color: #45a049;
    }

    .continue-btn {
      display: block;
      margin-top: 20px;
      padding: 10px;
      background-color: #2196F3;
      color: white;
      border: none;
      cursor: pointer;
    }

    .continue-btn:hover {
      background-color: #0b7dda;
    }
  </style>
</head>
<body>

<!-- Modal -->
<div id="trialModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" id="closeModalBtn">&times;</span>
    <h2>Which trial duration would you prefer?</h2>
    <div class="options">
      <button class="trial-btn" id="trial25min">⚡ 25 mins - Get to know the tutor, discuss your goals and learning plan</button>
      <button class="trial-btn" id="trial50min">📅 50 mins - Get everything in a 25-min lesson plus start learning</button>
    </div>
    <button id="continueBtn" class="continue-btn" style="display:none;">Continue</button>
  </div>
</div>

<script>
  // Get modal element and close button
  const modal = document.getElementById("trialModal");
  const closeBtn = document.getElementById("closeModalBtn");
  const continueBtn = document.getElementById("continueBtn");

  // Get the trial buttons
  const trial25minBtn = document.getElementById("trial25min");
  const trial50minBtn = document.getElementById("trial50min");

  // Handle trial duration selection and show "Continue" button
  let selectedDuration = '';

  trial25minBtn.onclick = function() {
    selectedDuration = '25min';
    continueBtn.style.display = 'block';
  }

  trial50minBtn.onclick = function() {
    selectedDuration = '50min';
    continueBtn.style.display = 'block';
  }

  // Continue button action (redirect based on selection)
  continueBtn.onclick = function() {
    if (selectedDuration === '25min') {
      window.location.href = 'Calendar.php?duration=25';
    } else if (selectedDuration === '50min') {
      window.location.href = 'Calendar.php?duration=50';
    }
  }

  // Close the modal and go to find_tutor.php
  closeBtn.onclick = function() {
    window.location.href = 'find_tutor.php';
  }

  // When clicking outside the modal, close it and go to find_tutor.php
  window.onclick = function(event) {
    if (event.target === modal) {
      window.location.href = 'find_tutor.php';
    }
  }
</script>

</body>
</html>
