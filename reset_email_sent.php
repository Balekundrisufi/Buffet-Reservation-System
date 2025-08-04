<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Password Reset Email Sent</title>
    <style>
        body {
            font-family:"DM Serif Display", serif;
            background-image: linear-gradient(to right,red,black);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color:transparent;
            padding: 20px;
            border-radius: 8px;
	    border-color:yellow;
	    border-style:solid;
            text-align: center;
        }
        h2 {
            color:yellow;
            margin-top: 0;
        }
        p {
            color: white;
        }
        .btn {
            background-color: yellow;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color:blue;
	    color:white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset Email Sent</h2>
        <p>An email with instructions to reset your password has been sent to your registered email address. Please check your inbox.</p>
        <a href="login.php" class="btn">Back to Login</a>
    </div>
</body>
</html>
