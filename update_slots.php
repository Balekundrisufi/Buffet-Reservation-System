<?php
// Set the time zone
date_default_timezone_set('Asia/Kolkata');

// Include your database configuration
include_once('includes/config.php');

$success_message = null;
$error_message = null;

// Get current date and the next day's date
$current_date = date('Y-m-d');
$next_day_date = date('Y-m-d', strtotime('+1 day'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission
    $date_to_update = $_POST['date_to_update'];
    $change_tables = intval($_POST['change_tables']);
    $operation = $_POST['operation']; // Get the selected operation

    // Retrieve current total and available tables for the given date
    $retrieve_sql = "SELECT slot_time, total_tables, available_tables FROM tblslots WHERE slot_date = ?";
    $retrieve_stmt = mysqli_prepare($con_booking, $retrieve_sql);
    mysqli_stmt_bind_param($retrieve_stmt, 's', $date_to_update);
    mysqli_stmt_execute($retrieve_stmt);
    $retrieve_result = mysqli_stmt_get_result($retrieve_stmt);

    if ($retrieve_result && mysqli_num_rows($retrieve_result) > 0) {
        while ($row = mysqli_fetch_assoc($retrieve_result)) {
            $slot_time = $row['slot_time'];
            $current_total_tables = $row['total_tables'];
            $current_available_tables = $row['available_tables'];

            if ($operation == 'increase') {
                // Calculate new total and available tables for increase operation
                $new_total_tables = $current_total_tables + $change_tables;
                $new_available_tables = $current_available_tables + $change_tables;
            } else {
                // Calculate new total and available tables for decrease operation
                $new_total_tables = $current_total_tables - $change_tables;
                $new_available_tables = $current_available_tables - $change_tables;
            }

            // Update total_tables and available_tables in the database
            $update_sql = "UPDATE tblslots SET total_tables = ?, available_tables = ? WHERE slot_date = ? AND slot_time = ?";
            $update_stmt = mysqli_prepare($con_booking, $update_sql);
            mysqli_stmt_bind_param($update_stmt, 'iiss', $new_total_tables, $new_available_tables, $date_to_update, $slot_time);
            $update_result = mysqli_stmt_execute($update_stmt);

            if ($update_result) {
                $success_message = "Slots for $date_to_update successfully updated.";
            } else {
                $error_message = "Failed to update slots for $date_to_update.";
            }

            // Close the statement
            mysqli_stmt_close($update_stmt);
        }
    } else {
        $error_message = "No slots found for the given date.";
    }

    // Close the retrieve statement
    mysqli_stmt_close($retrieve_stmt);
}

// Retrieve slots for today and tomorrow
$slots_sql = "SELECT * FROM tblslots WHERE slot_date = ? OR slot_date = ?";
$slots_stmt = mysqli_prepare($con_booking, $slots_sql);
mysqli_stmt_bind_param($slots_stmt, 'ss', $current_date, $next_day_date);
mysqli_stmt_execute($slots_stmt);
$slots_result = mysqli_stmt_get_result($slots_stmt);

// Organize slots data
$slots_data = [];
while ($row = mysqli_fetch_assoc($slots_result)) {
    $slots_data[$row['slot_date']][$row['slot_time']] = $row;
}

// Close the statement
mysqli_stmt_close($slots_stmt);

// Close the connection
mysqli_close($con_booking);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Update Slots</title>
    <style>
        body {
            font-family: "DM Serif Display", serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        header {
            background-color: brown;
            color: white;
            padding: 1rem;
            text-align: center;
			margin-bottom:10px;
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
		
        h2 {
            text-align: center;
            color: #343a40;
        }
        form {
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #495057;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: yellow;
            color: black;
            font-weight: bolder;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: brown;
            color: white;
        }
        .success-message, .error-message {
            font-weight: bold;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .success-message {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #dee2e6;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: brown;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Update Seats</h1>
    </header>
    <a href="admin_dash.php" class="back-button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to dash</a>
    <div class="container">
        <h2>Update Number of Seats</h2>
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php elseif ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="date_to_update">Date to Update:</label>
                <input type="date" id="date_to_update" name="date_to_update" min="<?php echo $current_date; ?>" max="<?php echo $next_day_date; ?>" required>
            </div>
            <div class="form-group">
                <label for="change_tables">Change Available Tables By:</label>
                <input type="number" id="change_tables" name="change_tables" required>
            </div>
            <div class="form-group">
                <label for="operation">Operation:</label>
                <select id="operation" name="operation" required>
                    <option value="increase">Increase</option>
                    <option value="decrease">Decrease</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Update Slots</button>
            </div>
        </form>

        <h2>Current Slots</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Total Tables</th>
                    <th>Available Tables</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($slots_data as $date => $slots): ?>
                    <?php foreach ($slots as $time_slot => $slot_info): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($date); ?></td>
                            <td><?php echo htmlspecialchars($time_slot); ?></td>
                            <td><?php echo htmlspecialchars($slot_info['total_tables']); ?></td>
                            <td><?php echo htmlspecialchars($slot_info['available_tables']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
