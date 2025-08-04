<?php
// Set the time zone
date_default_timezone_set('Asia/Kolkata');

// Include your database configuration
include_once('admin/includes/config.php');

$booking_details = [];
$error_message = null;
$success_message = null;

// Check if either email or phone number is provided
if (isset($_GET['email'])) {
    $email = isset($_GET['email']) ? trim($_GET['email']) : null;

    // Prepare the SQL query based on the available input
    if ($email) {
        $sql = "SELECT * FROM tblbookings WHERE email = ? ORDER BY booking_id DESC";
        $stmt = mysqli_prepare($con_booking, $sql);
        mysqli_stmt_bind_param($stmt, 's', $email);
    }

    // Execute the query if the statement is prepared successfully
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $booking_details[] = $row;
            }
        } else {
            $error_message = "No booking found with the provided Email or Phone Number.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $error_message = "Failed to prepare the query.";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_booking'])) {
        // Check if CSRF token is valid
        // Implement CSRF token validation here

        $booking_id = $_POST['booking_id'];

        foreach ($booking_details as &$booking) { // Use reference to modify the booking array
            if ($booking['booking_id'] == $booking_id && $booking['booking_status'] !== 'cancelled') {
                // Update booking status and arrival status to 'Cancelled'
                $update_sql = "UPDATE tblbookings SET booking_status = 'cancelled', arrival_status = 'cancelled' WHERE booking_id = ?";
                $update_stmt = mysqli_prepare($con_booking, $update_sql);
                mysqli_stmt_bind_param($update_stmt, 'i', $booking_id);
                $update_result = mysqli_stmt_execute($update_stmt);

                if ($update_result) {
                    // Update available slots
                    $slot_query = "UPDATE tblslots SET available_tables = available_tables + ? WHERE slot_date = ? AND slot_time = ?";
                    $slot_stmt = mysqli_prepare($con_booking, $slot_query);
                    $total_seats = $booking['num_adults'] + $booking['num_children'];
                    mysqli_stmt_bind_param($slot_stmt, 'iss', $total_seats, $booking['booking_date'], $booking['booking_time']);
                    mysqli_stmt_execute($slot_stmt);
                    mysqli_stmt_close($slot_stmt);

                    $success_message = "Booking successfully cancelled.";
                    // Refresh booking details
                    $booking['booking_status'] = 'cancelled';
                    $booking['arrival_status'] = 'cancelled';
                } else {
                    $error_message = "Failed to cancel the booking.";
                }

                // Close the statement
                mysqli_stmt_close($update_stmt);
                break; // Exit the loop once a booking is cancelled
            }
        }
    }

    // Close the connection
    mysqli_close($con_booking);
} else {
    $error_message = "No booking email or phone number provided.";
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
    <title>Booking Details</title>
    <style>
        body {
            font-family:"DM Serif Display", serif;
            background-image: linear-gradient(to right, red, black);
            margin: 20px;
        }
        .details-container {
            max-width: 800px;
            margin: auto;
        }
        .details-container h1 {
            color: yellow;
        }
        table {
            width: 100%;
            border-collapse: collapse;
	    margin-top:20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        td {
            color: white;
        }
        th {
            background-color: #f2f2f2;
        }
        .success-message, .error-message {
            font-weight: bold;
            padding: 10px;
            margin-bottom: 20px;
        }
        .success-message {
            color: white;
            text-align: center;
        }
        .error-message {
            color: white;
            text-align: center;
        }
        button {
            background-color: red;
            padding: 15px;
            border-radius: 10px;
	    margin:5px;
            color: white;
            font-weight: bolder;
            cursor: pointer;
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
		
		form{
			border-style:solid;
			border-color:white;
			margin-bottom:50px;
		}
		
        button:hover {
            background-color: blue;
        }
    </style>
</head>
<body>
	<a href="index.php" class="back-button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to form</a>
    <div class="details-container">
        <h1 style="text-align:center;">Booking Details</h1>
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php elseif ($success_message): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($booking_details): ?>
            <?php foreach ($booking_details as $booking): ?>
                <table>
                    <tr>
                        <th>Booking Id</th>
                        <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                    </tr>
                    <tr>
                        <th>Full Name</th>
                        <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($booking['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?php echo htmlspecialchars($booking['phone_number']); ?></td>
                    </tr>
                    <tr>
                        <th>Booking Date</th>
                        <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($booking['booking_date']))); ?></td>
                    </tr>
                    <tr>
                        <th>Booking Time</th>
                        <td><?php echo htmlspecialchars(date('H:i', strtotime($booking['booking_time']))); ?></td>
                    </tr>
                    <tr>
                        <th>Number of Adults</th>
                        <td><?php echo htmlspecialchars($booking['num_adults']); ?></td>
                    </tr>
                    <tr>
                        <th>Number of Children</th>
                        <td><?php echo htmlspecialchars($booking['num_children']); ?></td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td><?php echo htmlspecialchars('₹' . number_format($booking['total_amount'], 2)); ?></td>
                    </tr>
                    <tr>
                        <th>Booking Status</th>
                        <td><?php echo htmlspecialchars($booking['booking_status']); ?></td>
                    </tr>
                    <tr>
                        <th>Arrival Status</th>
                        <td><?php echo htmlspecialchars($booking['arrival_status']); ?></td>
                    </tr>
                </table>
                <?php if ($booking['booking_status'] !== 'cancelled'): ?>
                    <form action="" method="POST">
			<center>
                        <input type="hidden" name="cancel_booking" value="1">
                        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                        <button type="submit" class='button button-danger' onclick='return confirm("Are you sure you want to cancel?")'>Cancel Booking</button>
			</center>
                    </form>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
