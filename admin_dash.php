<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: "DM Serif Display", serif;
            background-image:linear-gradient(to right, #14f2f5, #45e31a);
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background:yellow;
            color: black;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            position: sticky;
			margin-bottom:10px;
            top: 0;
            z-index: 1000;
        }
		
        header h1 {
            margin: 0;
            font-size: 30px;
            letter-spacing: 1px;
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
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 0 15px;
        }

        section {
            flex: 1 1 300px;
            background:white;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            padding: 20px;
            box-sizing: border-box;
            transition: box-shadow 0.3s, transform 0.3s;
        }

        section:hover {
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        section h2 {
            margin-top: 0;
            font-size: 24px;
            color: #3a8dff;
            border-bottom: 2px solid #3a8dff;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            color:black;
            background-color: yellow;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .button:hover {
            background-color: blue;
			color:white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav a {
                font-size: 14px;
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    <header>
		<img src="../images/hand-fingers-crossed_10416112-removebg-preview.png" width="50px" height="50px" alt="logo">
        <h1>Admin Dashboard</h1>
    </header>
     <a href="../home_page.html" class="back_button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to home</a>
    <div class="container">
        <section id="update_slots">
            <h2>Update Slots</h2>
            <a href="update_slots.php" class="button">Go to Update Slots</a>
        </section>

        <section id="bookings_today">
            <h2>Bookings for Today</h2>
            <a href="bookings_today.php" class="button">View Bookings for Today</a>
        </section>

        <section id="bookings_tomorrow">
            <h2>Bookings for Tomorrow</h2>
            <a href="bookings_tomorrow.php" class="button">View Bookings for Tomorrow</a>
        </section>

        <section id="cancelled_bookings">
            <h2>Cancelled Bookings</h2>
            <a href="cancelled_bookings.php" class="button">View Cancelled Bookings</a>
        </section>

        <section id="admin_details">
            <h2>Admin Details</h2>
            <a href="admin_details.php" class="button">View Admin Details</a>
        </section>

        <section id="pricing">
            <h2>Update Pricing</h2>
            <a href="update_pricing.php" class="button">Go to Update Pricing</a>
        </section>

        <section id="all_bookings">
            <h2>View All Bookings</h2>
            <a href="all_bookings.php" class="button">View All Bookings</a>
        </section>
    </div>
</body>
</html>
