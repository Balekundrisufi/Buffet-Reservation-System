<?php
include_once('includes/config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $booking_id = intval($_GET['id']);

    // Check if booking exists
    $query = "SELECT booking_id, booking_status, booking_date, booking_time, num_adults, num_children FROM tblbookings WHERE booking_id = ?";
    if ($stmt = mysqli_prepare($con_booking, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $booking_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $booking = mysqli_fetch_assoc($result);

        if (!$booking) {
            die("Booking not found.");
        }

        if ($booking['booking_status'] === 'cancelled') {
            die("<p style='background-color:red; text-align:center;'>Booking is already cancelled.</p>");
        }

        // Cancel the booking
        $update_query = "UPDATE tblbookings SET booking_status = 'cancelled', arrival_status = 'cancelled' WHERE booking_id = ?";
        if ($update_stmt = mysqli_prepare($con_booking, $update_query)) {
            mysqli_stmt_bind_param($update_stmt, 'i', $booking_id);
            if (mysqli_stmt_execute($update_stmt)) {
                // Update available slots
                $slot_query = "UPDATE tblslots SET available_tables = available_tables + ? WHERE slot_date = ? AND slot_time = ?";
                if ($slot_stmt = mysqli_prepare($con_booking, $slot_query)) {
                    $total_seats = $booking['num_adults'] + $booking['num_children'];
                    mysqli_stmt_bind_param($slot_stmt, 'iss', $total_seats, $booking['booking_date'], $booking['booking_time']);
                    mysqli_stmt_execute($slot_stmt);
                    mysqli_stmt_close($slot_stmt);
                }
                $message = "<div class='message-success'>Booking ID $booking_id has been successfully cancelled.</div>";
            } else {
                $message = "<div class='message-error'>Error cancelling booking: " . mysqli_error($con_booking) . "</div>";
            }
            mysqli_stmt_close($update_stmt);
        } else {
            $message = "<div class='message-error'>Error preparing the update query: " . mysqli_error($con_booking) . "</div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing the query: " . mysqli_error($con_booking));
    }

    mysqli_close($con_booking);
} else {
    die("Invalid booking ID.");
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
    <title>Cancel Booking</title>
    <style>
        body {
            font-family: "DM Serif Display", serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
			margin-bottom:10px;
        }
		
		.back_button{
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
		.back_button:hover{
			color:white;
			background-color:blue;
		}
		
        .container {
            width: 80%;
            margin: 2rem auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        .button:hover {
            background-color: #555;
        }
        .message-container {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: center;
        }
        .message-success {
            border-color: #4caf50;
            background-color: #d4edda;
            color: #155724;
        }
        .message-error {
            border-color: #f44336;
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <header>
        <h1>Cancel Booking</h1>
    </header>
	<a href="admin_dash.php" class="back_button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to dash</a>
    <div class="container">
        <?php if (isset($message)) echo "<div class='message-container'>$message</div>"; ?>
        <?php if (isset($booking)): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <td><?php echo htmlspecialchars($booking_id); ?></td>
            </tr>
            <tr>
                <th>Booking Date</th>
                <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
            </tr>
            <tr>
                <th>Booking Time</th>
                <td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
            </tr>
            <tr>
                <th>Number of Adults</th>
                <td><?php echo htmlspecialchars($booking['num_adults']); ?></td>
            </tr>
            <tr>
                <th>Number of Children</th>
                <td><?php echo htmlspecialchars($booking['num_children']); ?></td>
            </tr>
        </table>
        <?php endif; ?>
        <a href="admin_dash.php" class="button">Back to dash</a>
    </div>
</body>
</html>
