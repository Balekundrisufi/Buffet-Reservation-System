<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Update Pricing</title>
    <style>
        body {
            font-family: "DM Serif Display", serif;
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
            color: #3a8dff;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input[type="number"], input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #3a8dff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bolder;
            text-align: center;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: blue;
			color:white;
        }
        
    </style>
</head>
<body>
    <header>
        <h1>Update Pricing</h1>
    </header>
	<a href="admin_dash.php" class="back_button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to dash</a>
    <div class="container">
        <section id="update_pricing">
            <h2>Update Pricing</h2>
            <?php
            include_once('includes/config.php');

            // Error handling
            if (!$con_booking) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            // Get current pricing
            $query = "SELECT price_adult, price_child, effective_date FROM tblpricing ORDER BY effective_date DESC LIMIT 1";
            $result = mysqli_query($con_booking, $query);

            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $adult_price = isset($row['price_adult']) ? $row['price_adult'] : '';
                $child_price = isset($row['price_child']) ? $row['price_child'] : '';
                $effective_date = isset($row['effective_date']) ? $row['effective_date'] : '';

                echo "<form action='process_update_pricing.php' method='post'>
                        <label for='adult_price'>Adult Price:</label>
                        <input type='number' id='adult_price' name='adult_price' step='0.01' value='" . htmlspecialchars($adult_price) . "' required>

                        <label for='child_price'>Child Price:</label>
                        <input type='number' id='child_price' name='child_price' step='0.01' value='" . htmlspecialchars($child_price) . "' required>

                        <label for='effective_date'>Effective Date:</label>
                        <input type='text' id='effective_date' name='effective_date' value='" . htmlspecialchars($effective_date) . "' required readonly>
                        <br>
                        <button type='submit' class='button'>Update Pricing</button>
                      </form>";
            } else {
                echo "<p>Error: " . mysqli_error($con_booking) . "</p>";
            }

            mysqli_close($con_booking);
            ?>
        </section>
    </div>
</body>
</html>
