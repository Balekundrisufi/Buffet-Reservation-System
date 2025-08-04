<?php
include_once('includes/config.php');

$isSuccess = true;

// Check the condition and display an alert if needed

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Sanitize and validate input
    $admin_id = trim($_GET['id']);
    
    if (empty($admin_id) || !filter_var($admin_id, FILTER_VALIDATE_INT)) {
        die("Invalid admin ID.");
    }

    // Check if the admin exists and is not the only admin
    $query_check = "SELECT COUNT(*) AS count FROM admins";
    $result_check = mysqli_query($con_login, $query_check);
    
    if ($result_check) {
        $row_check = mysqli_fetch_assoc($result_check);
        
        // Ensure there's more than one admin before deletion
        if ($row_check['count'] <= 1) {
            die("Cannot delete the only admin.");
        }
    } else {
        // Handle query execution error
        die("Error checking admin count: " . mysqli_error($con_login));
    }

    // Prepare and execute the delete query
    if ($stmt = mysqli_prepare($con_login, "DELETE FROM admins WHERE admin_id=?")) {
        mysqli_stmt_bind_param($stmt, "i", $admin_id);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect if successful
			if ($isSucess) {
                $Message = "deleted successfully!";
                echo "<script type='text/javascript'>
                        alert('$Message');
                        window.location.href = 'admin_details.php';
                      </script>";
                exit();
            }
        } else {
            // Handle query execution error
            die("Error executing query: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        // Handle query preparation error
        die("Error preparing statement: " . mysqli_error($con_login));
    }

    mysqli_close($con_login);
}
?>
