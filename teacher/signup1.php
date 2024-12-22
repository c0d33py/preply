<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please log in to access the form.");
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Create connection
include 'Connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$sql = "SELECT first_name, last_name, email FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user data
    $user = $result->fetch_assoc();
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
    $email = $user['email'];
} else {
    die("User data not found.");
}

if (isset($_POST['btnadd'])) {
    // Sanitize input to prevent SQL injection
// Sanitize input to prevent SQL injection
function sanitize($value) {
  global $conn;
  
  // Check if the input is an array (e.g., multi-select or checkboxes)
  if (is_array($value)) {
      // If it's an array, convert it to a string (for example, join array elements)
      return mysqli_real_escape_string($conn, implode(', ', $value));
  } else {
      // If it's a single string, sanitize it normally
      return mysqli_real_escape_string($conn, trim($value));
  }
}


    // Collect and sanitize inputs
    $firstName = sanitize($_POST['firstName'] ?? '');
    $lastName = sanitize($_POST['lastName'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $countryOfBirth = sanitize($_POST['countryOfBirth'] ?? '');
    $country = sanitize($_POST['country'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $languages = sanitize($_POST['languages'] ?? '');
    $level = sanitize($_POST['level'] ?? '');
    $phoneNumber = sanitize($_POST['phoneNumber'] ?? '');
    $photo = sanitize($_POST['photo'] ?? '');
    $certification = sanitize($_POST['certification'] ?? '');
    $education = sanitize($_POST['education'] ?? '');
    $intro = sanitize($_POST['intro'] ?? '');
    $experience = sanitize($_POST['experience'] ?? '');
    $motivation = sanitize($_POST['motivation'] ?? '');
    $headline = sanitize($_POST['headline'] ?? '');
    $video = sanitize($_POST['video'] ?? '');
    $timezone = sanitize($_POST['timezone'] ?? '');
    $pricing = sanitize($_POST['pricing'] ?? '');
    $mondayStartTime = sanitize($_POST['mondayStartTime'] ?? '');
    $mondayEndTime = sanitize($_POST['mondayEndTime'] ?? '');
    $tuesdayStartTime = sanitize($_POST['tuesdayStartTime'] ?? '');
    $tuesdayEndTime = sanitize($_POST['tuesdayEndTime'] ?? '');
    $wednesdayStartTime = sanitize($_POST['wednesdayStartTime'] ?? '');
    $wednesdayEndTime = sanitize($_POST['wednesdayEndTime'] ?? '');
    $thursdayStartTime = sanitize($_POST['thursdayStartTime'] ?? '');
    $thursdayEndTime = sanitize($_POST['thursdayEndTime'] ?? '');
    $fridayStartTime = sanitize($_POST['fridayStartTime'] ?? '');
    $fridayEndTime = sanitize($_POST['fridayEndTime'] ?? '');
    $saturdayStartTime = sanitize($_POST['saturdayStartTime'] ?? '');
    $saturdayEndTime = sanitize($_POST['saturdayEndTime'] ?? '');
    $sundayStartTime = sanitize($_POST['sundayStartTime'] ?? '');
    $sundayEndTime = sanitize($_POST['sundayEndTime'] ?? '');
    $balance = sanitize($_POST['balance'] ?? 0);
    $rating = sanitize($_POST['rating'] ?? 0);
    $reviews = sanitize($_POST['reviews'] ?? 0);
    $approvalStatus = 'not approved';
    $visibilityStatus = 'no';

    // Build the SQL query
    $query = "INSERT INTO teachers (
      first_name, last_name, email, country_of_birth, country, subject, languages, level, phone_number,
      photo, certification, education, intro, experience, motivation, headline, video, timezone, pricing,
      monday_start_time, monday_end_time, tuesday_start_time, tuesday_end_time,
      wednesday_start_time, wednesday_end_time, thursday_start_time, thursday_end_time,
      friday_start_time, friday_end_time, saturday_start_time, saturday_end_time,
      sunday_start_time, sunday_end_time, created_at, updated_at, balance, rating, reviews, approval_status, user_id, visibility_status
    ) VALUES (
      '$firstName', '$lastName', '$email', '$countryOfBirth', '$country', '$subject', '$languages', '$level', '$phoneNumber',
      '$photo', '$certification', '$education', '$intro', '$experience', '$motivation', '$headline', '$video', '$timezone', '$pricing',
      '$mondayStartTime', '$mondayEndTime', '$tuesdayStartTime', '$tuesdayEndTime', 
      '$wednesdayStartTime', '$wednesdayEndTime', '$thursdayStartTime', '$thursdayEndTime', 
      '$fridayStartTime', '$fridayEndTime', '$saturdayStartTime', '$saturdayEndTime', 
      '$sundayStartTime', '$sundayEndTime', NOW(), NOW(), $balance, $rating, $reviews, '$approvalStatus', $user_id, '$visibilityStatus'
    )";

    // Execute the query
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('New record created successfully');</script>";
        header("Location: signup_pic.php");
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    // Close the connection
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
        <div class="step active" >
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
        <div class="step inactive">
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
  <title>Sign Up</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4>Sign Up</h4>
          </div>
          <div class="card-body">
            <form action="signup1.php" method="post">
              <!-- First Name -->
              <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $firstName; ?>"required>
              </div>
              <!-- Last Name -->
              <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $lastName; ?>"required>
              </div>
              <!-- Email -->
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>"required>
              </div>
              <!-- Country of Birth -->
              <div class="form-group">
  <label for="countryOfBirth">Country of Birth</label>
  <select class="form-control" id="countryOfBirth" name="countryOfBirth" required>
    <option value="" disabled selected>Select your country</option>
    <option value="Afghanistan">Afghanistan</option>
    <option value="Albania">Albania</option>
    <option value="Algeria">Algeria</option>
    <option value="Andorra">Andorra</option>
    <option value="Angola">Angola</option>
    <option value="Argentina">Argentina</option>
    <option value="Armenia">Armenia</option>
    <option value="Australia">Australia</option>
    <option value="Austria">Austria</option>
    <option value="Azerbaijan">Azerbaijan</option>
    <option value="Bahamas">Bahamas</option>
    <option value="Bahrain">Bahrain</option>
    <option value="Bangladesh">Bangladesh</option>
    <option value="Barbados">Barbados</option>
    <option value="Belarus">Belarus</option>
    <option value="Belgium">Belgium</option>
    <option value="Belize">Belize</option>
    <option value="Benin">Benin</option>
    <option value="Bhutan">Bhutan</option>
    <option value="Bolivia">Bolivia</option>
    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
    <option value="Botswana">Botswana</option>
    <option value="Brazil">Brazil</option>
    <option value="Brunei">Brunei</option>
    <option value="Bulgaria">Bulgaria</option>
    <option value="Burkina Faso">Burkina Faso</option>
    <option value="Burundi">Burundi</option>
    <option value="Cambodia">Cambodia</option>
    <option value="Cameroon">Cameroon</option>
    <option value="Canada">Canada</option>
    <option value="Cape Verde">Cape Verde</option>
    <option value="Central African Republic">Central African Republic</option>
    <option value="Chad">Chad</option>
    <option value="Chile">Chile</option>
    <option value="China">China</option>
    <option value="Colombia">Colombia</option>
    <option value="Comoros">Comoros</option>
    <option value="Congo">Congo</option>
    <option value="Costa Rica">Costa Rica</option>
    <option value="Croatia">Croatia</option>
    <option value="Cuba">Cuba</option>
    <option value="Cyprus">Cyprus</option>
    <option value="Czech Republic">Czech Republic</option>
    <option value="Denmark">Denmark</option>
    <option value="Djibouti">Djibouti</option>
    <option value="Dominica">Dominica</option>
    <option value="Dominican Republic">Dominican Republic</option>
    <option value="Ecuador">Ecuador</option>
    <option value="Egypt">Egypt</option>
    <option value="El Salvador">El Salvador</option>
    <option value="Equatorial Guinea">Equatorial Guinea</option>
    <option value="Eritrea">Eritrea</option>
    <option value="Estonia">Estonia</option>
    <option value="Eswatini">Eswatini</option>
    <option value="Ethiopia">Ethiopia</option>
    <option value="Fiji">Fiji</option>
    <option value="Finland">Finland</option>
    <option value="France">France</option>
    <option value="Gabon">Gabon</option>
    <option value="Gambia">Gambia</option>
    <option value="Georgia">Georgia</option>
    <option value="Germany">Germany</option>
    <option value="Ghana">Ghana</option>
    <option value="Greece">Greece</option>
    <option value="Grenada">Grenada</option>
    <option value="Guatemala">Guatemala</option>
    <option value="Guinea">Guinea</option>
    <option value="Guinea-Bissau">Guinea-Bissau</option>
    <option value="Guyana">Guyana</option>
    <option value="Haiti">Haiti</option>
    <option value="Honduras">Honduras</option>
    <option value="Hungary">Hungary</option>
    <option value="Iceland">Iceland</option>
    <option value="India">India</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Iran">Iran</option>
    <option value="Iraq">Iraq</option>
    <option value="Ireland">Ireland</option>
    <option value="Israel">Israel</option>
    <option value="Italy">Italy</option>
    <option value="Ivory Coast">Ivory Coast</option>
    <option value="Jamaica">Jamaica</option>
    <option value="Japan">Japan</option>
    <option value="Jordan">Jordan</option>
    <option value="Kazakhstan">Kazakhstan</option>
    <option value="Kenya">Kenya</option>
    <option value="Kiribati">Kiribati</option>
    <option value="Kuwait">Kuwait</option>
    <option value="Kyrgyzstan">Kyrgyzstan</option>
    <option value="Laos">Laos</option>
    <option value="Latvia">Latvia</option>
    <option value="Lebanon">Lebanon</option>
    <option value="Lesotho">Lesotho</option>
    <option value="Liberia">Liberia</option>
    <option value="Libya">Libya</option>
    <option value="Liechtenstein">Liechtenstein</option>
    <option value="Lithuania">Lithuania</option>
    <option value="Luxembourg">Luxembourg</option>
    <option value="Madagascar">Madagascar</option>
    <option value="Malawi">Malawi</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Maldives">Maldives</option>
    <option value="Mali">Mali</option>
    <option value="Malta">Malta</option>
    <option value="Marshall Islands">Marshall Islands</option>
    <option value="Mauritania">Mauritania</option>
    <option value="Mauritius">Mauritius</option>
    <option value="Mexico">Mexico</option>
    <option value="Micronesia">Micronesia</option>
    <option value="Moldova">Moldova</option>
    <option value="Monaco">Monaco</option>
    <option value="Mongolia">Mongolia</option>
    <option value="Montenegro">Montenegro</option>
    <option value="Morocco">Morocco</option>
    <option value="Mozambique">Mozambique</option>
    <option value="Myanmar">Myanmar</option>
    <option value="Namibia">Namibia</option>
    <option value="Nauru">Nauru</option>
    <option value="Nepal">Nepal</option>
    <option value="Netherlands">Netherlands</option>
    <option value="New Zealand">New Zealand</option>
    <option value="Nicaragua">Nicaragua</option>
    <option value="Niger">Niger</option>
    <option value="Nigeria">Nigeria</option>
    <option value="North Korea">North Korea</option>
    <option value="Norway">Norway</option>
    <option value="Oman">Oman</option>
    <option value="Pakistan">Pakistan</option>
    <option value="Palau">Palau</option>
    <option value="Palestine">Palestine</option>
    <option value="Panama">Panama</option>
    <option value="Papua New Guinea">Papua New Guinea</option>
    <option value="Paraguay">Paraguay</option>
    <option value="Peru">Peru</option>
    <option value="Philippines">Philippines</option>
    <option value="Poland">Poland</option>
    <option value="Portugal">Portugal</option>
    <option value="Qatar">Qatar</option>
    <option value="Romania">Romania</option>
    <option value="Russia">Russia</option>
    <option value="Rwanda">Rwanda</option>
    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
    <option value="Saint Lucia">Saint Lucia</option>
    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
    <option value="Samoa">Samoa</option>
    <option value="San Marino">San Marino</option>
    <option value="Saudi Arabia">Saudi Arabia</option>
    <option value="Senegal">Senegal</option>
    <option value="Serbia">Serbia</option>
    <option value="Seychelles">Seychelles</option>
    <option value="Sierra Leone">Sierra Leone</option>
    <option value="Singapore">Singapore</option>
    <option value="Slovakia">Slovakia</option>
    <option value="Slovenia">Slovenia</option>
    <option value="Solomon Islands">Solomon Islands</option>
    <option value="Somalia">Somalia</option>
    <option value="South Africa">South Africa</option>
    <option value="South Korea">South Korea</option>
    <option value="Spain">Spain</option>
    <option value="Sri Lanka">Sri Lanka</option>
    <option value="Sudan">Sudan</option>
    <option value="Suriname">Suriname</option>
    <option value="Sweden">Sweden</option>
    <option value="Switzerland">Switzerland</option>
    <option value="Syria">Syria</option>
    <option value="Taiwan">Taiwan</option>
    <option value="Tajikistan">Tajikistan</option>
    <option value="Tanzania">Tanzania</option>
    <option value="Thailand">Thailand</option>
    <option value="Togo">Togo</option>
    <option value="Tonga">Tonga</option>
    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="Tunisia">Tunisia</option>
    <option value="Turkey">Turkey</option>
    <option value="Turkmenistan">Turkmenistan</option>
    <option value="Tuvalu">Tuvalu</option>
    <option value="Uganda">Uganda</option>
    <option value="Ukraine">Ukraine</option>
    <option value="United Arab Emirates">United Arab Emirates</option>
    <option value="United Kingdom">United Kingdom</option>
    <option value="United States">United States</option>
    <option value="Uruguay">Uruguay</option>
    <option value="Uzbekistan">Uzbekistan</option>
    <option value="Vanuatu">Vanuatu</option>
    <option value="Vatican City">Vatican City</option>
    <option value="Venezuela">Venezuela</option>
    <option value="Vietnam">Vietnam</option>
    <option value="Yemen">Yemen</option>
    <option value="Zambia">Zambia</option>
    <option value="Zimbabwe">Zimbabwe</option>
   
  </select>
</div>

<div class="form-group">
  <label for="subject">Subject You Teach</label>
  <select class="form-control" id="subject" name="subject" required>
    <option value="" disabled selected>Select your subject</option>
    <option value="English">English</option>
    <option value="Urdu">Urdu</option>
    <option value="Spanish">Spanish</option>
    <option value="French">French</option>
    <option value="German">German</option>
    <option value="Italian">Italian</option>
    <option value="Arabic">Arabic</option>
    <option value="Mandarin Chinese">Mandarin Chinese</option>
    <option value="Hindi">Hindi</option>
    <option value="Russian">Russian</option>
    <option value="Portuguese">Portuguese</option>
    <option value="Japanese">Japanese</option>
    <option value="Korean">Korean</option>
    <option value="Turkish">Turkish</option>
    <option value="Greek">Greek</option>
    <option value="Latin">Latin</option>
    <option value="Bengali">Bengali</option>
    <option value="Punjabi">Punjabi</option>
    <option value="Swahili">Swahili</option>
    <option value="Dutch">Dutch</option>
    <option value="Polish">Polish</option>
    <option value="Swedish">Swedish</option>
    <option value="Norwegian">Norwegian</option>
    <option value="Danish">Danish</option>
  </select>
</div>

              <!-- Languages You Speak -->
              <div class="form-group">
              <div class="form-group">
  <label for="languages">Languages You Speak</label>
  <div id="languageContainer">
    <div class="d-flex align-items-center mb-2">
      <!-- Language Dropdown -->
      <select class="form-control mr-2" name="languages[]" required>
      <option value="" disabled selected>Select your Language</option>
    <option value="English">English</option>
    <option value="Urdu">Urdu</option>
    <option value="Spanish">Spanish</option>
    <option value="French">French</option>
    <option value="German">German</option>
    <option value="Italian">Italian</option>
    <option value="Arabic">Arabic</option>
    <option value="Mandarin Chinese">Mandarin Chinese</option>
    <option value="Hindi">Hindi</option>
    <option value="Russian">Russian</option>
    <option value="Portuguese">Portuguese</option>
    <option value="Japanese">Japanese</option>
    <option value="Korean">Korean</option>
    <option value="Turkish">Turkish</option>
    <option value="Greek">Greek</option>
    <option value="Latin">Latin</option>
    <option value="Bengali">Bengali</option>
    <option value="Punjabi">Punjabi</option>
    <option value="Swahili">Swahili</option>
    <option value="Dutch">Dutch</option>
    <option value="Polish">Polish</option>
    <option value="Swedish">Swedish</option>
    <option value="Norwegian">Norwegian</option>
    <option value="Danish">Danish</option>
        <!-- Add more languages as needed -->
      </select>

      <!-- Level Dropdown -->
      <select class="form-control" name="level[]" required>
        <option value="" disabled selected>Select your level</option>
        <option value="Native">Native</option>
        <option value="Fluent">Fluent</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Beginner">Beginner</option>
      </select>
    </div>
  </div>
  
  <!-- Add Another Language -->
  <div>
    <a href="#" id="addLanguage" class="text-primary" style="text-decoration: underline; cursor: pointer;">Add another language</a>
  </div>
</div>
              <!-- Phone Number -->
              <div class="form-group">
  <label for="phoneNumber">Phone Number <span class="text-muted">(optional)</span></label>
  <div class="d-flex">
    <!-- Country Code Dropdown -->
    <select class="form-control w-25" id="countryCode" name="countryCode" required>
      <option value="" disabled selected>Select Country</option>
      <option value="+1">USA (+1)</option>
      <option value="+44">UK (+44)</option>
      <option value="+92">Pakistan (+92)</option>
      <option value="+91">India (+91)</option>
      <option value="+61">Australia (+61)</option>
      <option value="+81">Japan (+81)</option>
      <option value="+49">Germany (+49)</option>
      <option value="+33">France (+33)</option>
      <option value="+34">Spain (+34)</option>
      <option value="+55">Brazil (+55)</option>
      <option value="+86">China (+86)</option>
      <option value="+62">Indonesia (+62)</option>
      <option value="+39">Italy (+39)</option>
      <option value="+7">Russia (+7)</option>
      <option value="+27">South Africa (+27)</option>
      <option value="+52">Mexico (+52)</option>
      <option value="+20">Egypt (+20)</option>
      <!-- Add more countries here as needed -->
    </select>
    
    <!-- Phone Number Input -->
    <input type="text" class="form-control ml-2" id="phoneNumber" name="phoneNumber" placeholder="Phone number" required>
  </div>
</div>

              <!-- Age Confirmation -->
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="ageConfirmation" name="ageConfirmation" required>
                <label class="form-check-label" for="ageConfirmation">I confirm I’m over 18</label>
              </div>
              <!-- Submit Button -->
              <button type="submit" class="btn btn-primary btn-block" name="btnadd" id="btnadd">Save and continue</button>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
<script>
  // JavaScript to dynamically add more language fields
  document.getElementById('addLanguage').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default link behavior
    
    const languageContainer = document.getElementById('languageContainer');
    const newLanguageField = document.createElement('div');
    newLanguageField.className = 'd-flex align-items-center mb-2';
    newLanguageField.innerHTML = `
      <!-- Language Dropdown -->
      <select class="form-control mr-2" name="languages[]" required>
    <option value="" disabled selected>Select your Language</option>
    <option value="English">English</option>
    <option value="Urdu">Urdu</option>
    <option value="Spanish">Spanish</option>
    <option value="French">French</option>
    <option value="German">German</option>
    <option value="Italian">Italian</option>
    <option value="Arabic">Arabic</option>
    <option value="Mandarin Chinese">Mandarin Chinese</option>
    <option value="Hindi">Hindi</option>
    <option value="Russian">Russian</option>
    <option value="Portuguese">Portuguese</option>
    <option value="Japanese">Japanese</option>
    <option value="Korean">Korean</option>
    <option value="Turkish">Turkish</option>
    <option value="Greek">Greek</option>
    <option value="Latin">Latin</option>
    <option value="Bengali">Bengali</option>
    <option value="Punjabi">Punjabi</option>
    <option value="Swahili">Swahili</option>
    <option value="Dutch">Dutch</option>
    <option value="Polish">Polish</option>
    <option value="Swedish">Swedish</option>
    <option value="Norwegian">Norwegian</option>
    <option value="Danish">Danish</option>
        <!-- Add more languages as needed -->
      </select>
      
      <!-- Level Dropdown -->
      <select class="form-control" name="level[]" required>
        <option value="" disabled selected>Select your level</option>
        <option value="Native">Native</option>
        <option value="Fluent">Fluent</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Beginner">Beginner</option>
      </select>
      
      <!-- Recycle Bin Icon -->
      <button type="button" class="btn btn-danger btn-sm ml-2 removeLanguage">
        <i class="fas fa-trash"></i>
      </button>
    `;
    languageContainer.appendChild(newLanguageField);

    // Add event listener to remove button
    newLanguageField.querySelector('.removeLanguage').addEventListener('click', function () {
      languageContainer.removeChild(newLanguageField);
    });
  });
</script>

<!-- Add FontAwesome for the Recycle Bin Icon -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
