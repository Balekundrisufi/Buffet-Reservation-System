<?php
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST["booking_id"];
    $new_adults = $_POST["new_adults"];
    $new_children = $_POST["new_children"];

    // Fetch current pricing
    $stmt = $con_booking->prepare("SELECT price FROM pricing WHERE seat_type = 'Adult' ORDER BY effective_date DESC LIMIT 1");
    $stmt->execute();
    $stmt->bind_result($adult_price);
    $stmt->fetch();
    $stmt->close();

    $stmt = $con_booking->prepare("SELECT price FROM pricing WHERE seat_type = 'Child' ORDER BY effective_date DESC LIMIT 1");
    $stmt->execute();
    $stmt->bind_result($child_price);
    $stmt->fetch();
    $stmt->close();

    // Calculate the new total amount
    $new_total_amount = ($new_adults * $adult_price) + ($new_children * $child_price);

    // Update the booking
    $stmt = $con_booking->prepare("UPDATE tblbookings SET no_adults = ?, no_children = ?, total_amount = ? WHERE booking_id = ?");
    $stmt->bind_param("iidi", $new_adults, $new_children, $new_total_amount, $booking_id);
    
    if ($stmt->execute()) {
        echo "Booking updated successfully.";
    } else {
        echo "Error updating booking: " . $stmt->error;
    }
    
    $stmt->close();
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
    <title>Edit Booking</title>
	<style>
	body {
         font-family: "DM Serif Display", serif;
        }
	</style>
</head>
<body>
    <h1>Edit Booking</h1>
    <form method="post">
        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
        <label for="new_adults">New Number of Adults:</label>
        <input type="number" id="new_adults" name="new_adults" required><br><br>
        
        <label for="new_children">New Number of Children:</label>
        <input type="number" id="new_children" name="new_children" required><br><br>
        
        <button type="submit">Update Booking</button>
    </form>
</body>
</html>
