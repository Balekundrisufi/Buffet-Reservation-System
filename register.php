<?php
session_start();

// Include configuration file
include_once('admin/includes/config.php');

// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = $phone_number = "";
$username_err = $password_err = $confirm_password_err = $email_err = $phone_number_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT user_id FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($con_login, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                $error_message = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate phone number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter your phone number.";
    } elseif (!preg_match('/^[0-9]{10}$/', trim($_POST["phone_number"]))) {
        $phone_number_err = "Phone number must be exactly 10 digits.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', trim($_POST["email"]))) {
        $email_err = "Invalid email format. Only Gmail accounts are allowed.";
    } else {
        $sql = "SELECT user_id FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($con_login, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already registered.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                $error_message = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter a password.";     
    } elseif (strlen(trim($_POST['password'])) < 8) {
        $password_err = "Password must have at least 8 characters.";
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($phone_number_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email, phone_number) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($con_login, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_email, $param_phone_number);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;
            $param_phone_number = $phone_number;
            if (mysqli_stmt_execute($stmt)) {
                // Store registration details in session
                $_SESSION['register_username'] = $username;
                $_SESSION['register_password'] = $password;

                // Redirect to login page
                header("location: login.php");
                exit;
            } else {
                $error_message = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($con_login);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Sign Up</title>
    <style>
        body {
            font-family: 'DM Serif Display', serif;
            background-image: url('images/bg.jpg');
            background-size: cover;  
            background-repeat: no-repeat; 
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .wrapper {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-style: solid;
            border-color: yellow;
            border-radius: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: center;
            position: relative;
        }

        .wrapper img {
            position: absolute;
            top: -18px;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: yellow;
            color: black;
            font-weight: bold;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            width: 130px; 
            height: 40px; 
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: blue;
            color: white;
            font-weight: bold;
        }

        span.error {
            color: red;
            font-size: 12px;
        }

        p {
            margin-top: 16px;
            font-size: 14px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <img src="images/signup_icon.png" alt="Signup Icon">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <input type="text" style="margin-top:100px;" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
                <span class="error"><?php echo $username_err; ?></span>
            </div>
            <div>
                <input type="text" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div>
                <input type="text" name="phone_number" placeholder="Phone Number" value="<?php echo htmlspecialchars($phone_number); ?>">
                <span class="error"><?php echo $phone_number_err; ?></span>
            </div>
            <div>
                <input type="password" name="password" placeholder="Password">
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div>
                <input type="password" name="confirm_password" placeholder="Confirm Password">
                <span class="error"><?php echo $confirm_password_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Register">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
