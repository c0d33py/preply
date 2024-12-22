<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <style>/* Footer Styling */
        .custom-footer {
            background-color: #1a1a1a; /* Dark background */
            color: #fff; /* White text */
            padding: 40px 30px;
            font-family: Arial, sans-serif;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        .footer-sections {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: space-between;
        }
        
        .section h4 {
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #fff;
        }
        
        .section ul {
            list-style: none;
            padding: 0;
        }
        
        .section ul li {
            margin-bottom: 8px;
        }
        
        .section ul li a {
            color: #bbb;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .section ul li a:hover {
            color: #4CAF50; /* Highlight color */
        }
        
        .footer-bottom {
            display: flex;
            flex-wrap: wrap;
            gap: 129px;
            justify-content: space-between;
        }
        
        .footer-support, .footer-contacts, .footer-social, .footer-apps {
            flex: 1 1 200px;
        }
        
        .footer-social ul {
            list-style: none;
            padding: 0;
        }
        .footer-social img {
            display: inline-block;
            width: 20px;
            height: 20px;
        }
        
        .footer-social ul li {
            margin-bottom: 8px;
        }
        
        .footer-social ul li a {
            color: #bbb;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-social ul li a img:hover {
            color: #4CAF50;
        }
        
        .footer-apps ul li {
            list-style-type: none;
        }
        
        .footer-apps img {
            margin-right: 10px;
            transition: transform 0.3s ease;
        }
        
        .footer-apps img:hover {
            transform: scale(1.1);
        }
        
        .last ul {
            display: flex;
            justify-content: center;
            gap: 15px;
            list-style-type: none;
        }
        
        .last ul li a {
            color: #fff;
            transition: color 0.3s ease;
        }
        
        .last ul li a:hover {
            color: #4CAF50;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .footer-sections, .footer-bottom {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
        }
        </style>
</head>

<body>
    
   
      <footer class="custom-footer">
        <div class="footer-container">
            <!-- Main Sections -->
            <div class="footer-sections">
                <div class="section">
                    <h4>About Us</h4>
                    <ul>
                        <li><a href="#">Who we are</a></li>
                        <li><a href="#">How it works</a></li>
                        <li><a href="#">Reviews</a></li>
                        <li><a href="#">Affiliate program</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>
                <div class="section">
                    <h4>For Students</h4>
                    <ul>
                        <li><a href="#">Student blog</a></li>
                        <li><a href="#">Discounts</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Test your English</a></li>
                    </ul>
                </div>
                <div class="section">
                    <h4>For Tutors</h4>
                    <ul>
                        <li><a href="#">Become a tutor</a></li>
                        <li><a href="#">Teach online</a></li>
                        <li><a href="#">Tutor resources</a></li>
                    </ul>
                </div>
                <div class="section">
                    <h4>For Companies</h4>
                    <ul>
                        <li><a href="#">Corporate training</a></li>
                        <li><a href="#">Language programs</a></li>
                        <li><a href="#">Resource center</a></li>
                    </ul>
                </div>
            </div>
    
            <!-- Additional Info -->
            <div class="footer-bottom">
                <div class="footer-support">
                    <h4>Support</h4>
                    <p><a href="#">Need any help?</a></p>
                </div>
                <div class="footer-contacts">
                    <h4>Contacts</h4>
                    <p> Pakistan</p>
                    <p> 17km Raiwind Rd, Kot Araian <br>Lahore</p>
                </div>
                <div class="footer-social">
                    <h4>Social Media</h4>
                    <ul>
                        <li><a href="https://www.facebook.com/"><img src="Images\facebook.png" width="100">Facebook</a></li>  
                        <li><a href="https://www.instagram.com/accounts/login/?hl=en"><img src="Images\instagram.jpeg"  width="100">Instagram</a></li>
                        <li><a href="https://www.youtube.com/"><img src="Images\youtube.png" width="100">Youtube</a> </li>
                        <li><a href="https://x.com/i/flow/login?lang=en"><img src="Images\X.png" width="100"> X</a></li>

                    </ul>
                </div>
                <div class="footer-apps">
                    <h4>Apps</h4>
                    
                        <ul>
                            <li><a href="#"><img src="Images\app_store.png" alt="App Store" width="100"></a></li>
                            <li><a href="#"><img src="Images\playstore.png" alt="Google Play" width="100"></a></li>
                        </ul>
                    
                </div>

            </div>

            <div class="last">
                <p style="text-align: center">&copy; 2024 Student Portal. All rights reserved.</p>
                <ul>
                    <li><a href="Privacy policy.pdf">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>

        </div>
        
    </footer>
    
    
    
</body>
</html>
