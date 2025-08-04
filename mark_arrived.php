<?php
include_once('includes/config.php');

if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);

    $update_query = "UPDATE tblbookings SET arrival_status = 'reached' WHERE booking_id = ?";
    if ($stmt = mysqli_prepare($con_booking, $update_query)) {
        mysqli_stmt_bind_param($stmt, 'i', $booking_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con_booking);
}

header('Location: bookings_today.php'); 
exit();
?>
