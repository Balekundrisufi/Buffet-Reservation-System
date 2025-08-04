<?php
include_once('includes/config.php');

// Function to check seat availability
function check_seat_availability($booking_date, $booking_time, $num_adults, $num_children, $con_booking) {
    // Fetch available tables for the given date and time
    $query = "SELECT available_tables FROM tblslots WHERE slot_date = ? AND slot_time = ?";
    if ($stmt = mysqli_prepare($con_booking, $query)) {
        mysqli_stmt_bind_param($stmt, 'ss', $booking_date, $booking_time);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        if (!$row) {
            die("Slot information not found.");
        }
        $available_tables = $row['available_tables'];

        // Calculate the total number of seats needed
        $total_seats_needed = $num_adults + $num_children;

        // Check if there are enough available tables
        return $available_tables >= $total_seats_needed;
    } else {
        die("Error preparing the query: " . mysqli_error($con_booking));
    }
}

$message = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $booking_id = intval($_GET['id']);

    // Fetch current booking details
    $query = "SELECT * FROM tblbookings WHERE booking_id = ?";
    if ($stmt = mysqli_prepare($con_booking, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $booking_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $booking = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$booking) {
            die("Booking not found.");
        }

        // Fetch current pricing
        $pricing_query = "SELECT * FROM tblpricing ORDER BY effective_date DESC LIMIT 1";
        $pricing_result = mysqli_query($con_booking, $pricing_query);
        $pricing = mysqli_fetch_assoc($pricing_result);

        if (!$pricing) {
            die("Pricing not found.");
        }

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['form_submitted'] == '0') {
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $booking_date = $_POST['booking_date'];
            $booking_time = $_POST['booking_time'];
            $num_adults = intval($_POST['num_adults']);
            $num_children = intval($_POST['num_children']);
            $total_amount = $num_adults * $pricing['price_adult'] + $num_children * $pricing['price_child'];

            $total_seats_needed = $num_adults + $num_children;
            $total_seats_current = $booking['num_adults'] + $booking['num_children'];

            if ($total_seats_needed > $total_seats_current) {
                if (!check_seat_availability($booking_date, $booking_time, $num_adults, $num_children, $con_booking)) {
                    $message = "<div class='message-error'>Not enough seats available for the requested number of guests.</div>";
                } else {
                    // Update booking
                    $update_query = "UPDATE tblbookings SET full_name = ?, email = ?, phone_number = ?, booking_date = ?, booking_time = ?, num_adults = ?, num_children = ?, total_amount = ? WHERE booking_id = ?";
                    if ($update_stmt = mysqli_prepare($con_booking, $update_query)) {
                        mysqli_stmt_bind_param($update_stmt, 'sssssiidi', $full_name, $email, $phone_number, $booking_date, $booking_time, $num_adults, $num_children, $total_amount, $booking_id);
                        if (mysqli_stmt_execute($update_stmt)) {
                            // Decrease available tables
                            $tables_to_decrease = $total_seats_needed - $total_seats_current;
                            $update_slots_query = "UPDATE tblslots SET available_tables = available_tables - ? WHERE slot_date = ? AND slot_time = ?";
                            if ($update_slots_stmt = mysqli_prepare($con_booking, $update_slots_query)) {
                                mysqli_stmt_bind_param($update_slots_stmt, 'iss', $tables_to_decrease, $booking_date, $booking_time);
                                mysqli_stmt_execute($update_slots_stmt);
                                mysqli_stmt_close($update_slots_stmt);
                            }
                            $message = "<div class='message-success'>Booking updated successfully.</div>";
                        } else {
                            $message = "<div class='message-error'>Error updating booking: " . mysqli_stmt_error($update_stmt) . "</div>";
                        }
                        mysqli_stmt_close($update_stmt);
                    } else {
                        $message = "<div class='message-error'>Error preparing the update query: " . mysqli_error($con_booking) . "</div>";
                    }
                }
            } else {
                // Update booking if no increase in number of seats
                $update_query = "UPDATE tblbookings SET full_name = ?, email = ?, phone_number = ?, booking_date = ?, booking_time = ?, num_adults = ?, num_children = ?, total_amount = ? WHERE booking_id = ?";
                if ($update_stmt = mysqli_prepare($con_booking, $update_query)) {
                    mysqli_stmt_bind_param($update_stmt, 'sssssiidi', $full_name, $email, $phone_number, $booking_date, $booking_time, $num_adults, $num_children, $total_amount, $booking_id);
                    if (mysqli_stmt_execute($update_stmt)) {
                        // Increase available tables
                        $tables_to_increase = $total_seats_current - $total_seats_needed;
                        $update_slots_query = "UPDATE tblslots SET available_tables = available_tables + ? WHERE slot_date = ? AND slot_time = ?";
                        if ($update_slots_stmt = mysqli_prepare($con_booking, $update_slots_query)) {
                            mysqli_stmt_bind_param($update_slots_stmt, 'iss', $tables_to_increase, $booking_date, $booking_time);
                            mysqli_stmt_execute($update_slots_stmt);
                            mysqli_stmt_close($update_slots_stmt);
                        }
                        $message = "<div class='message-success'>Booking updated successfully.</div>";
                    } else {
                        $message = "<div class='message-error'>Error updating booking: " . mysqli_stmt_error($update_stmt) . "</div>";
                    }
                    mysqli_stmt_close($update_stmt);
                } else {
                    $message = "<div class='message-error'>Error preparing the update query: " . mysqli_error($con_booking) . "</div>";
                }
            }
        }
    } else {
        die("Error preparing the query: " . mysqli_error($con_booking));
    }
} else {
    die("Invalid request.");
}

mysqli_close($con_booking);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Edit Booking</title>
    <style>
		body {
            font-family: "DM Serif Display", serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: orange;
            color: #fff;
            padding: 10px 0;
            text-align: center;
			margin-bottom:10px;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            background-color: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        form {
            width: 100%;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        input[type="text"], input[type="email"], input[type="date"], input[type="time"], input[type="number"], select {
            width: calc(100% - 22px);
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .message-container {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
			text-align:center;
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
	<h2>Edit Booking</h2>
    </header>
	<a href="admin_dash.php" class="back_button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to dash</a>
	<div class="container">
		<div id="message" class="message-container"><?php echo $message; ?></div>
        <form method="POST">
            <input type="hidden" id="form_submitted" name="form_submitted" value="0">
            <table>
                <tr>
                    <th><label for="full_name">Full Name:</label></th>
                    <td><input type="text" name="full_name" value="<?php echo htmlspecialchars($booking['full_name']); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="email">Email:</label></th>
                    <td><input type="email" name="email" value="<?php echo htmlspecialchars($booking['email']); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="phone_number">Phone Number:</label></th>
                    <td><input type="text" name="phone_number" value="<?php echo htmlspecialchars($booking['phone_number']); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="booking_date">Booking Date:</label></th>
                    <td><input type="date" name="booking_date" value="<?php echo htmlspecialchars($booking['booking_date']); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="booking_time">Booking Time:</label></th>
                    <td><input type="time" name="booking_time" value="<?php echo htmlspecialchars($booking['booking_time']); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="num_adults">Number of Adults:</label></th>
                    <td><input type="number" name="num_adults" value="<?php echo htmlspecialchars($booking['num_adults']); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="num_children">Number of Children:</label></th>
                    <td><input type="number" name="num_children" value="<?php echo htmlspecialchars($booking['num_children']); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="total_amount">Total Amount:</label></th>
                    <td><input type="text" name="total_amount" value="<?php echo htmlspecialchars($booking['total_amount']); ?>" readonly></td>
                </tr>
                <tr>
                    <td colspan="2"><button type="submit">Update Booking</button></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
