<?php
// Set the time zone
date_default_timezone_set('Asia/Kolkata');

// Include your database configuration
include_once('admin/includes/config.php');

// Initialize variables
$error_message = null;
$redirect_url = null;

// Check if database connection is established
if (!$con_booking) {
    die('Database connection failed: ' . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');

    // Check if at least one input is provided
    if (empty($email) && empty($phone_number)) {
        $error_message = "Please enter your email.";
    } else {
        // Base query
        $sql = "SELECT email FROM tblbookings WHERE 1=1";

        // Append condition based on input
        if (!empty($email)) {
            $sql .= " AND email = ?";
            $stmt = mysqli_prepare($con_booking, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $email);
            } else {
                $error_message = "Query preparation failed.";
            }
        } 

        if ($stmt) {
            // Execute query
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Check if any result is found
            if (mysqli_num_rows($result) > 0) {
                $booking = mysqli_fetch_assoc($result);
                $redirect_url = "booking_details.php?email=$email";
            } else {
                $error_message = "No booking found with the provided information.";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        }

    }

    // Close connections
    mysqli_close($con_booking);

    // Redirect if URL is set
    if ($redirect_url) {
        header("Location: $redirect_url");
        exit();
    }
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
    <title>Check Booking Status</title>
    <style>
        body {
            font-family:"DM Serif Display", serif;
	        background-image:linear-gradient(to right,red,black);
            margin: 20px;
        }
		
		.back-button{
			border-radius:10px;
			border-style:solid;
			background-color:yellow;
			color:black;
			padding:10px;
			text-decoration:none;
			margin-left:6.1%;
			font-weight:bolder;
			transition:background-color 0.3s;
			
		}
		.back-button:hover{
			color:white;
			background-color:blue;
		}
		
        .form-container {
            max-width: 600px;
            margin: auto;
			border-style:solid;
			border-color:yellow;
			padding:20px;
        }
		.form-container h1{
			color:yellow;
		}
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
			color:white;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: yellow;
            color: black;
	    font-weight:bolder;
	    border-radius:5px;
            border: none;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: blue;
			color:white;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to form</a>
    <div class="form-container">
        <h1>Check Booking Status</h1>
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Enter Email:</label>
                <input type="text" id="email" name="email">
            </div>
            <div class="form-group">
                <button type="submit">Check Status</button>
            </div>
        </form>
    </div>
</body>
</html>
