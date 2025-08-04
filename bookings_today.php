<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Bookings for Today</title>
    <style>
        body {
            font-family: "DM Serif Display", serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #4CAF50;
            color: white;
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
            background-color: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 0.75rem;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        p {
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 8px 16px;
            margin: 4px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
        }
        .button.edit {
            background-color: #28a745;
        }
        .button.cancel {
            background-color: #dc3545;
        }
        .button.arrived {
            background-color: #ffc107;
        }
        .button:hover {
            opacity: 0.8;
        }
        .total-earnings {
            font-weight: bold;
            text-align: right;
            padding: 1rem 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bookings for Today</h1>
    </header>
	<a href="admin_dash.php" class="back_button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to dash</a>
    <div class="container">
        <section id="today_bookings">
            <h2>Today's Bookings</h2>
            <?php
            include_once('includes/config.php');

            $today = date('Y-m-d');
            $query = "SELECT booking_id, full_name, (num_adults + num_children) AS number_of_seats, booking_time AS time_slot, booking_status AS status, arrival_status, total_amount 
                      FROM tblbookings 
                      WHERE booking_date = ?";
            if ($stmt = mysqli_prepare($con_booking, $query)) {
                mysqli_stmt_bind_param($stmt, 's', $today);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                $total_earnings = 0;

                if (mysqli_num_rows($result) > 0) {
                    echo "<table>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer Name</th>
                                <th>Number of Seats</th>
                                <th>Time Slot</th>
                                <th>Status</th>
                                <th>Arrival Status</th>
                                <th>Total Amount</th>
                                <th>Actions</th>
                            </tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $booking_id = $row['booking_id'];
                        echo "<tr>
                                <td>{$row['booking_id']}</td>
                                <td>{$row['full_name']}</td>
                                <td>{$row['number_of_seats']}</td>
                                <td>{$row['time_slot']}</td>
                                <td>{$row['status']}</td>
                                <td>{$row['arrival_status']}</td>
                                <td>{$row['total_amount']}</td>
                                <td>
                                    <a href='edit_booking.php?id={$booking_id}' class='button edit'>Edit</a>
                                    <a href='cancel_booking.php?id={$booking_id}' class='button cancel' onclick='return confirm(\"Are you sure you want to cancel this booking?\")'>Cancel</a>
                                    <a href='mark_arrived.php?id={$booking_id}' class='button arrived'>Mark as Arrived</a>
                                </td>
                              </tr>";
                        if ($row['status'] === 'confirmed' && $row['arrival_status'] === 'reached') {
                            $total_earnings += $row['total_amount'];
                        }
                    }
                    echo "</table>";
                    echo "<div class='total-earnings'>Total Earnings: ₹" . number_format($total_earnings, 2) . "</div>";
                } else {
                    echo "<p>No bookings for today.</p>";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "<p>Error preparing the query: " . mysqli_error($con_booking) . "</p>";
            }

            mysqli_close($con_booking);
            ?>
        </section>
    </div>
</body>
</html>
