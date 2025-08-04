<?php
include_once('includes/config.php');

$isSuccess = true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adult_price = trim($_POST['adult_price']);
    $child_price = trim($_POST['child_price']);

    // Ensure prices are valid numbers
    if (!is_numeric($adult_price) || !is_numeric($child_price)) {
        die("Invalid input. Please enter valid numbers for prices.");
    }

    // Insert new pricing with the current date
    $query = "INSERT INTO tblpricing (price_adult, price_child, effective_date)
              VALUES (?, ?, CURDATE())
              ON DUPLICATE KEY UPDATE price_adult = VALUES(price_adult), price_child = VALUES(price_child), effective_date = VALUES(effective_date)";
    
    if ($stmt = mysqli_prepare($con_booking, $query)) {
        mysqli_stmt_bind_param($stmt, "dd", $adult_price, $child_price);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect if successful
			if ($isSuccess) {
                $Message = "updated successfully!";
                echo "<script type='text/javascript'>
                        alert('$Message');
                        window.location.href = 'update_pricing.php';
                      </script>";
                exit();
            }
        } else {
            die("Error executing query: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing statement: " . mysqli_error($con_booking));
    }

    mysqli_close($con_booking);
}
?>
