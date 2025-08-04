<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Cancelled Bookings</title>
    <style>
        body {
            font-family: "DM Serif Display", serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #ff4d4d;
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
            color: #ff4d4d;
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
            background-color: #ff4d4d;
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
    </style>
</head>
<body>
    <header>
        <h1>Cancelled Bookings</h1>
    </header>
	<a href="admin_dash.php" class="back_button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to dash</a>
    <div class="container">
        <section id="cancelled_bookings_list">
            <h2>Cancelled Bookings</h2>
            <?php
            // Database connection
            include_once('includes/config.php');

            // Query to get cancelled bookings
            $query = "SELECT booking_id, full_name, (num_adults + num_children) AS number_of_seats, booking_date, booking_time
                      FROM tblbookings 
                      WHERE booking_status = 'Cancelled'";
            $result = mysqli_query($con_booking, $query);

            if (mysqli_num_rows($result) > 0) {
                echo "<table>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer Name</th>
                            <th>Number of Seats</th>
                            <th>Booking Date</th>
                            <th>Booking Time</th>
                        </tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['booking_id']}</td>
                            <td>{$row['full_name']}</td>
                            <td>" . ($row['number_of_seats']) . "</td>
                            <td>{$row['booking_date']}</td>
                            <td>{$row['booking_time']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No cancelled bookings.</p>";
            }

            mysqli_close($con_booking);
            ?>
        </section>
    </div>
</body>
</html>
