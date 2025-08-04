<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>All Bookings</title>
    <style>
        body {
            font-family:  "DM Serif Display", serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #3a8dff;
            color: white;
            padding: 1rem;
            text-align: center;
			margin-bottom:10px;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
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
		
        h2 {
            color: #3a8dff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3a8dff;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <header>
        <h1>All Bookings</h1>
    </header>
	<a href="admin_dash.php" class="back_button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to dash</a>
    <div class="container">
        <section id="all_bookings_list">
            <h2>All Bookings</h2>
            <?php
            // Database connection
            include_once('includes/config.php');

            // Check connection
            if (!$con_booking) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch all bookings
            $query = "SELECT booking_id, full_name, num_adults, num_children, booking_date, booking_status FROM tblbookings";
            $result = mysqli_query($con_booking, $query);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<table>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer Name</th>
                                <th>Number of Adults</th>
                                <th>Number of Children</th>
                                <th>Status</th>
                                <th>Booking Date</th>
                            </tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['booking_id']}</td>
                                <td>{$row['full_name']}</td>
                                <td>{$row['num_adults']}</td>
                                <td>{$row['num_children']}</td>
                                <td>{$row['booking_status']}</td>
                                <td>{$row['booking_date']}</td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No bookings available.</p>";
                }
            } else {
                echo "<p>Error: " . mysqli_error($con_booking) . "</p>";
            }

            mysqli_close($con_booking);
            ?>
        </section>
    </div>
</body>
</html>
