<?php
include_once('admin/includes/config.php'); // Include the database connection

// Get booking number from URL
$email = mysqli_real_escape_string($con_booking, $_GET['email']);

// Fetch booking details
$booking_query = "SELECT * FROM tblbookings WHERE email = '$email'";
$booking_result = mysqli_query($con_booking, $booking_query);
$booking = mysqli_fetch_assoc($booking_result);

if ($booking) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
        <title>Booking Confirmation</title>
        <style>
            body {
                font-family: "DM Serif Display", serif;
                background-image:linear-gradient(to right,red,black);
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
			
            .confirmation-container {
                background-color: transparent;
                border-radius: 12px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                padding: 30px;
				padding-top:10px;
				margin:20px;
				margin-top:100px;
                max-width: 600px;
                width: 80%;
                text-align: left;
                border: 1px solid #ddd;
            }
            .confirmation-container h1 {
                color: yellow;
                margin-bottom: 20px;
                font-size: 32px;
                font-weight: 700;
                border-bottom: 3px solid #007bff;
                padding-bottom: 10px;
            }
            .confirmation-container p {
                margin: 15px 0;
                font-size: 18px;
                color: white;
                line-height: 1.6;
            }
            .confirmation-container p strong {
                color: white;
                font-weight: 700;
            }
            .confirmation-container .total-amount {
                font-size: 22px;
                font-weight: 700;
                color: white;
                margin-top: 20px;
                border-top: 2px solid #ddd;
                padding-top: 15px;
            }
            .confirmation-container a {
                display: inline-block;
                margin-top: 10px;
                padding: 12px 24px;
                background-color: yellow;
                color: black;
                text-decoration: none;
                border-radius: 8px;
                font-size: 18px;
                font-weight: 600;
                transition: background-color 0.3s, transform 0.2s;
                text-align: center;
            }
            .confirmation-container a:hover {
                background-color: blue;
				color:white;
                transform: translateY(-2px);
            }
            .confirmation-container a:active {
                background-color: #003d80;
                transform: translateY(0);
            }
            .not-found-container {
                background-color: #ffffff;
                border-radius: 12px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                padding: 30px;
                max-width: 800px;
                width: 90%;
                text-align: center;
                border: 1px solid #ddd;
                margin: 20px;
            }
            .not-found-container h1 {
                color: #dc3545;
                margin-bottom: 20px;
                font-size: 32px;
                font-weight: 700;
            }
            .not-found-container p {
                margin: 15px 0;
                font-size: 18px;
                color: #495057;
                line-height: 1.6;
            }
            .not-found-container a {
                display: inline-block;
                margin-top: 30px;
                padding: 12px 24px;
                background-color: #007bff;
                color: #ffffff;
                text-decoration: none;
                border-radius: 8px;
                font-size: 18px;
                font-weight: 600;
                transition: background-color 0.3s, transform 0.2s;
                text-align: center;
            }
            .not-found-container a:hover {
                background-color: #0056b3;
                transform: translateY(-2px);
            }
            .not-found-container a:active {
                background-color: #003d80;
                transform: translateY(0);
            }
            @media (max-width: 600px) {
                .confirmation-container, .not-found-container {
                    padding: 20px;
                }
                .confirmation-container h1, .not-found-container h1 {
                    font-size: 28px;
                }
                .confirmation-container p, .not-found-container p {
                    font-size: 16px;
                }
                .confirmation-container a, .not-found-container a {
                    font-size: 16px;
                    padding: 10px 20px;
                }
            }
        </style>
    </head>
    <body>
        <?php if ($booking) { ?>
            <div class="confirmation-container">
                <h1>Booking Confirmation</h1>
                <p>Your booking has been successfully confirmed.</p>
                <p><strong>Booking Id:</strong> <?php echo htmlspecialchars($booking['booking_id']); ?></p>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($booking['full_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($booking['phone_number']); ?></p>
                <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($booking['booking_date']); ?></p>
                <p><strong>Booking Time:</strong> <?php echo htmlspecialchars($booking['booking_time']); ?></p>
                <p><strong>Number of Adults:</strong> <?php echo htmlspecialchars($booking['num_adults']); ?></p>
                <p><strong>Number of Children:</strong> <?php echo htmlspecialchars($booking['num_children']); ?></p>
                <p class="total-amount"><strong>Total Amount:</strong> ₹<?php echo htmlspecialchars($booking['total_amount']); ?></p>
	        <a href="index.php">Go Back</a>
            </div>
        <?php } else { ?>
            <div class="not-found-container">
                <h1>Booking Not Found</h1>
                <p>The booking number you entered does not exist. Please check the number and try again.</p>
		<a href="index.php">Go Back</a>
            </div>
        <?php } ?>
    </body>
    </html>
    <?php
} else {
    echo "<div class='not-found-container'><h1>Booking Not Found</h1><p>The booking id you entered does not exist. Please check the number and try again.</p><a href='index.php'>Return to Homepage</a></div>";
}
?>
