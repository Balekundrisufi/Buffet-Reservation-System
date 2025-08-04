<?php
include_once('admin/includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_option = $_POST['date'];
    $time = $_POST['time'];

    // Convert date option to actual date
    $date = $date_option === 'today' ? date('Y-m-d') : date('Y-m-d', strtotime('+1 day'));

    $query = "SELECT available_tables FROM tblslots WHERE slot_date = '$date' AND slot_time = '$time'";
    $result = mysqli_query($con_booking, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        echo $row['available_tables'];
    } else {
        echo 'No data available';
    }
}
?>
