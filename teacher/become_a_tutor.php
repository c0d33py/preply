<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Tutor Signup Page</title>  
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">  
    <style>  
        body {  
            font-family: 'Inter', sans-serif;  
            margin: 0;  
            padding: 0;  
            background-color: #FFFFFF; /* White background */  
            display: flex;  
            justify-content: center;  
            align-items: center;  
            height: 100vh;  
            text-align: left;  
            color: #333; /* Dark text for readability */  
        }  

        .container {  
            max-width: 1200px;  
            display: flex;  
            align-items: center;  
        }  

        .text-content {  
            flex: 1;  
            padding-right: 40px;  
        }  

        h1 {  
            font-size: 2.5rem;  
            margin-bottom: 20px;  
            font-weight: 600;  
            color: #6C3D66; /* Dark Purple for heading */  
        }  

        .steps {  
            display: flex;  
            margin: 20px 0;  
            align-items: center;  
        }  

        .step {  
            background-color: #D8A7CA; /* Light Lilac background */  
            border-radius: 10px;  
            padding: 20px;  
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  
            margin-right: 20px;  
            text-align: center;  
        }  

        .step:last-child {  
            margin-right: 0;  
        }  

        .step h2 {  
            font-size: 2rem;  
            margin: 10px 0;  
            font-weight: 600;  
            color: #9B59B6; /* Purple for step numbers */  
        }  

        .step p {  
            font-size: 0.9rem;  
            color: #4D3346; /* Soft Purple for description */  
        }  

        .btn {  
            background-color: #9B59B6; /* Primary button purple */  
            border: 2px solid transparent;  
            color: white;  
            padding: 15px 30px;  
            border-radius: 5px;  
            font-size: 1.2rem;  
            font-weight: 600;  
            cursor: pointer;  
            margin-top: 20px;  
            display: inline-block;  
            transition: background-color 0.3s, border 0.3s;  
            text-decoration: none;  
        }  

        .btn:hover {  
            background-color: #8E44AD; /* Darker purple on hover */  
            border: 2px solid #FFFFFF;  
        }  

        .image-container {  
            flex: 1;  
            display: flex;  
            justify-content: center;  
            align-items: center;  
        }  

        img {  
            max-width: 100%;  
            border-radius: 10px;  
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);  
        }  
    </style>  
</head>  
<body>  
    <div class="container">  
        <div class="text-content">  
            <h1>Make a living by teaching the largest community of learners worldwide</h1>  
            <div class="steps">  
                <div class="step">  
                    <h2>1</h2>  
                    <p>Sign up<br>to create your tutor profile</p>  
                </div>  
                <div class="step">  
                    <h2>2</h2>  
                    <p>Get approved<br>by our team in 5 business days</p>  
                </div>  
                <div class="step">  
                    <h2>3</h2>  
                    <p>Start earning<br>by teaching students all over the world!</p>  
                </div>  
            </div>  
            <a href="#" class="btn">Create a tutor profile now</a>  
        </div>  
        <div class="image-container">  
            <img src="learning.jpg" alt="Tutor Image">   
        </div>  
    </div>  
</body>  
</html>
