<?php
session_start();
include('includes/config.php');

if(isset($_POST['resetpwd'])) {
    $uname = $_POST['username'];
    $email = $_POST['email'];
    $newpassword = $_POST['newpassword'];
    $confirmpassword = $_POST['confirmpassword'];

    if($newpassword != $confirmpassword) {
        echo "<script>alert('New Password and Confirm Password do not match!');</script>";
    } else {
        // Check if username and email are correct
        $sql = mysqli_query($con_login, "SELECT admin_id FROM admins WHERE username='$uname' AND email='$email'");
        $rowcount = mysqli_num_rows($sql);

        if($rowcount > 0) {
            // Hash the new password
            $hashedPassword = password_hash($newpassword, PASSWORD_DEFAULT);
            // Update the password in the database
            $query = mysqli_query($con_login, "UPDATE admins SET password='$hashedPassword' WHERE username='$uname' AND email='$email'");

            if($query) {
                echo "<script>alert('Your Password successfully changed');</script>";
                echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
            } else {
                echo "<script>alert('Error updating password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid username or email.');</script>";
        }
    }
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
  <title>Restaurant Table Booking System | Password Recovery</title>
  <style>
    body {
        background-image: linear-gradient(to right, red, black);
        background-size: 100% 100%;
        padding-top: 3%;
		font-family: "DM Serif Display", serif;
        margin: 0;
    }
    .admin-text {
        font-size: 40px;
        color: lightblue;
    }
    #sign-in-text {
        font-size: 17px;
        color: white;
        font-weight: bold;
    }
    .wrapper {
        background-color: transparent;
        padding: 0 30px;
        padding-bottom: 10px;
        margin: auto;
        border-radius: 30px;
        border-color: white;
        border-style: solid;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        width: 24%;
        text-align: center;
    }
    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }
    .profile-icons {
        width: 30px;
        height: 30px;
        display: block;
    }
    input[type="text"], input[type="email"], input[type="password"] {
        display: block;
        border-radius: 10px;
        height: 30px;
        width: 100%;
    }
    .btn-primary {
        background-color: yellow;
        color: black;
        border: none;
        margin: auto;
        font-weight: bolder;
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
        width: 90px;
    }
    .btn-primary:hover {
        background-color: blue;
        color: white;
    }
  </style>
</head>
<body>
<center>
</center>
    <div class="wrapper">
        <b class="admin-text">Admin | BKS</b><hr>
        <p id="sign-in-text">Reset your password</p>
        <br>
        <form name="passwordrecovery" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <img class="profile-icons" src="../images/profile.svg">
                <input type="text" id="username" name="username" placeholder="USERNAME" required>
            </div>
            <div class="form-group">
                <img class="profile-icons" src="../images/mailicon.svg">
                <input type="email" id="email" name="email" placeholder="EMAIL" required>
            </div>
            <div class="form-group">
                <img class="profile-icons" src="../images/key.svg">
                <input type="password" id="newpassword" name="newpassword" placeholder="NEW PASSWORD" required>
            </div>
            <div class="form-group">
                <img class="profile-icons" src="../images/key.svg">
                <input type="password" id="confirmpassword" name="confirmpassword" placeholder="CONFIRM PASSWORD" required>
            </div>
            <div class="form-group">
                <center>
                    <input type="submit" class="btn-primary" value="Reset" name="resetpwd">
                </center>
            </div>
        </form>
    </div>
</body>
</html>
