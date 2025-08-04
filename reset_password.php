<?php
session_start();

// Include configuration file
include_once('admin/includes/config.php');

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
$password_changed = false; // Variable to track password change status

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 8) {
        $new_password_err = "Password must have at least 8 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before updating the database
    if (empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE user_id = ?";
        
        if ($stmt = mysqli_prepare($con_login, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["user_id"]; // Ensure session variable matches your table column
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Set password change status to true
                $password_changed = true;
            } else {
                $error_msg = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($con_login);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Reset Password</title>
    <style>
        body {
            font-family:"DM Serif Display", serif;
            background-image: linear-gradient(to right, red, black);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
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

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .error {
            color: #ff0000;
            margin-top: 5px;
        }

        .submit-btn {
            background-color: yellow;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: blue;
            color: white;
        }

        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" required>
                <?php echo !empty($new_password_err) ? '<div class="error">' . htmlspecialchars($new_password_err) . '</div>' : ''; ?>
            </div>    
            <div>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <?php echo !empty($confirm_password_err) ? '<div class="error">' . htmlspecialchars($confirm_password_err) . '</div>' : ''; ?>
            </div>
            <div>
                <input type="submit" value="Submit" class="submit-btn">
            </div>
        </form>
    </div>

    <?php if ($password_changed): ?>
        <script>
            alert("Password changed successfully.");
            window.location.href = 'login.php'; // Redirect to login page after showing the alert
        </script>
    <?php endif; ?>
</body>
</html>
