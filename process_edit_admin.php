<?php
include_once('includes/config.php');

$isSuccess = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $admin_id = trim($_POST['admin_id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    // Validate required fields
    if (empty($admin_id) || empty($username) || empty($email)) {
        die("Required fields are missing.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Prepare the update query
    $query = "UPDATE admins SET username=?, email=?";
    $params = [$username, $email];

    // Add password to the query if provided
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", password=?";
        $params[] = $password_hash;
    }

    $query .= " WHERE admin_id=?";

    // Prepare and execute the query
    if ($stmt = mysqli_prepare($con_login, $query)) {
        // Bind parameters
        if (!empty($password)) {
            mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $password_hash, $admin_id);
        } else {
            mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $admin_id);
        }

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
           if ($isSuccess) {
                $Message = "Edited successfully!";
                echo "<script type='text/javascript'>
                        alert('$Message');
                        window.location.href = 'admin_details.php';
                      </script>";
                exit();
            }
        } else {
            // Handle query execution error
            error_log("Error executing query: " . mysqli_stmt_error($stmt)); // Log the error
            die("Error executing query. Please try again later.");
        }

        mysqli_stmt_close($stmt);
    } else {
        // Handle query preparation error
        error_log("Error preparing statement: " . mysqli_error($con_login)); // Log the error
        die("Error preparing statement. Please try again later.");
    }

    mysqli_close($con_login);
}
?>
