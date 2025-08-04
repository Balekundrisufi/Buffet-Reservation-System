<?php
session_start();

// Include configuration file and other necessary PHP scripts
include_once('admin/includes/config.php');

// Process form submission
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Retrieve form data
    $email = trim($_POST["email"]);

    // Check if email exists in the database
    $sql = "SELECT user_id FROM users WHERE email = ?";
    
    if($stmt = mysqli_prepare($con_login, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        
        // Set parameters
        $param_email = $email;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Store result
            mysqli_stmt_store_result($stmt);
            
            // Check if email exists
            if(mysqli_stmt_num_rows($stmt) == 1){
                // Send password reset link to the user's email
                $reset_link = "http://localhost/bfs/reset_password.php?email=" . $email;
                $to = $email;
                $subject = "Password Reset Request";
                $message = "Please click the following link to reset your password: " . $reset_link;
                
                // PHPMailer setup
                require 'PHPMailer/src/PHPMailer.php';
                require 'PHPMailer/src/SMTP.php';
                require 'PHPMailer/src/Exception.php';

                $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'adityadhamnekar123@gmail.com'; // Your Gmail email address
                $mail->Password = 'oykzesbowwhgoxfw'; // Your Gmail password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('adityadhamnekar123@gmail.com', 'Buffet Reservation');
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $message;

                if ($mail->send()) {
                    // Redirect user to a page indicating that an email has been sent
                    header("location: reset_email_sent.php");
                    exit;
                } else {
                    $error_msg = "Something went wrong. Please try again later.";
                }
            } else {
                $error_msg = "No account found with that email address.";
            }
        } else {
            $error_msg = "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
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
    <title>Forgot Password</title>
    <style>
        body {
            font-family: "DM Serif Display", serif;
            background-image: linear-gradient(to right, red, black);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .wrapper {
            background-color: transparent;
            border-style: solid;
            border-color: yellow;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: center;
        }

        h2 {
            margin-top: 0;
            color: yellow;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }

        input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin: 0 10px;
            color: black;
        }

        input[type="submit"] {
            background-color: yellow;
            color: black;
            font-weight: bold;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: blue;
            color: white;
        }

        .error-message {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Forgot Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-group">
            <label for="email">Email Id</label>
            <input type="email" name="email" placeholder="Enter your registered email id" id="email" required>
            <br><br>
            <input type="submit" value="Reset Password">
        </form>
        <?php if(isset($error_msg)) { ?>
            <div class="error-message"><?php echo $error_msg; ?></div>
        <?php } ?>
    </div>
</body>
</html>
