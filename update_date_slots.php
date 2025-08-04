<?php
// Database connection
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password
$dbname = "booking";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get today's date
$today = date('Y-m-d'); // Stores today's date in YYYY-MM-DD format, e.g., 2024-07-28
$tomorrow = date('Y-m-d', strtotime('+1 day')); // Stores the date of the next day, e.g., 2024-07-29
$dayAfterTomorrow = date('Y-m-d', strtotime('+2 days')); // Stores the date of the day after tomorrow, e.g., 2024-07-30

// Delete slots for the current date
$sql_delete_today = "DELETE FROM tblslots WHERE slot_date = '$today'"; // SQL query to delete all slots for today
if ($conn->query($sql_delete_today) === TRUE) {
    echo "Deleted slots for $today successfully.<br>"; // Success message
} else {
    echo "Error deleting slots: " . $conn->error . "<br>"; // Error message
}

// Fetch the total_tables value from the previous date (tomorrow)
$sql_fetch_total_tables = "SELECT total_tables FROM tblslots WHERE slot_date = '$tomorrow' LIMIT 1"; // SQL query to fetch total tables for tomorrow
$result_total_tables = $conn->query($sql_fetch_total_tables);

if ($result_total_tables->num_rows > 0) {
    $row_total_tables = $result_total_tables->fetch_assoc();
    $total_tables = $row_total_tables['total_tables']; // Stores the total tables for tomorrow
} else {
    $total_tables = 20; // Default value if no record found for tomorrow
}

// Fetch slots for tomorrow
$sql_fetch_slots = "SELECT slot_time FROM tblslots WHERE slot_date = '$tomorrow'"; // SQL query to fetch slot times for tomorrow
$result = $conn->query($sql_fetch_slots);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $slot_time = $row['slot_time']; // Stores the time of each slot for tomorrow

        // Add slots for the day after tomorrow with total tables fetched from tomorrow's record
        $sql_insert_day_after_tomorrow = "INSERT INTO tblslots (slot_date, slot_time, available_tables, total_tables) VALUES ('$dayAfterTomorrow', '$slot_time', $total_tables, $total_tables)"; // SQL query to insert new slots for the day after tomorrow
        if ($conn->query($sql_insert_day_after_tomorrow) === TRUE) {
            echo "Added slot for $dayAfterTomorrow at $slot_time with $total_tables available tables successfully.<br>"; // Success message
        } else {
            echo "Error adding slot: " . $conn->error . "<br>"; // Error message
        }
    }
} else {
    echo "No slots found for $tomorrow.<br>"; // Message if no slots are found for tomorrow
}

// Close connection
$conn->close();
?>
