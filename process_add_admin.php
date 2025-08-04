<?php
include_once('includes/config.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = mysqli_real_escape_string($con_login, $_POST['username']);
    $email = mysqli_real_escape_string($con_login, $_POST['email']);
    $password = mysqli_real_escape_string($con_login, $_POST['password']);
    $full_name = mysqli_real_escape_string($con_login, $_POST['full_name']);
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new admin
    $query = "INSERT INTO admins (username, password, email, full_name) VALUES ('$username', '$hashed_password', '$email', '$full_name')";
    
    if (mysqli_query($con_login, $query)) {
        echo "New admin added successfully.";
    } else {
        echo "Error: " . mysqli_error($con_login);
    }

    mysqli_close($con_login);
}
?>
