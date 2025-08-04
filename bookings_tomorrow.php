<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Bookings for Tomorrow</title>
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
        .action-links a {
            text-decoration: none;
            color: #4CAF50;
            margin: 0 5px;
            font-weight: bold;
        }
        .action-links a:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bookings for Tomorrow</h1>
    </header>
	<a href="admin_dash.php" class="back_button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to dash</a>
    <div class="container">
        <section id="tomorrow_bookings">
            <h2>Tomorrow's Bookings</h2>
            <?php
            include_once('includes/config.php');

            $tomorrow = date('Y-m-d', strtotime('+1 day'));

            // Use prepared statements to prevent SQL injection
            $query = "SELECT booking_id, full_name, (num_adults + num_children) AS number_of_seats, booking_time AS time_slot, booking_status AS status 
                      FROM tblbookings 
                      WHERE booking_date = ?";
            if ($stmt = mysqli_prepare($con_booking, $query)) {
                mysqli_stmt_bind_param($stmt, 's', $tomorrow);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    echo "<table>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer Name</th>
                                <th>Number of Seats</th>
                                <th>Time Slot</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $booking_id = htmlspecialchars($row['booking_id']);
                        echo "<tr>
                                <td>{$booking_id}</td>
                                <td>{$row['full_name']}</td>
                                <td>{$row['number_of_seats']}</td>
                                <td>{$row['time_slot']}</td>
                                <td>{$row['status']}</td>
                                <td class='action-links'>
                                    <a href='edit_booking.php?id={$booking_id}'>Edit</a> |
                                    <a href='cancel_booking.php?id={$booking_id}'>Cancel</a>
                                </td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No bookings for tomorrow.</p>";
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
