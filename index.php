<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
    $uname = $_POST['username'];
    $password = $_POST['inputpwd'];
    $query = mysqli_query($con_login, "SELECT admin_id, username, password FROM admins WHERE username='$uname'");
    $ret = mysqli_fetch_array($query);

    if ($ret && password_verify($password, $ret['password'])) {
        $_SESSION['aid'] = $ret['admin_id'];
        $_SESSION['uname'] = $ret['username'];
        header('location:admin_dash.php');
        exit();
    } else {
        echo "<script>alert('Invalid Details.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Table Reservation System | Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to right, red, black);
            background-size: 100% 100%;
            padding-top: 3%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'DM Serif Display', serif;
        }
        .wrapper {
            background-color: transparent;
            padding: 30px;
            border-radius: 30px;
            border: 2px solid white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 360px;
            text-align: center;
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
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .profile-icons {
            width: 30px;
            height: 30px;
            vertical-align: middle;
            margin-right: 10px;
        }
        input[type="text"], input[type="password"] {
            display: block;
            border-radius: 10px;
            height: 30px;
            width: 100%;
            padding: 5px;
            margin-top: 5px;
        }
        .btn-primary {
            background-color: yellow;
            color: black;
            border: none;
            margin: auto;
            font-weight: bold;
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
        .signup-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .signup-link:hover {
            text-decoration: underline;
            color: yellow;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <b class="admin-text">Admin | BKS</b><hr>
        <p id="sign-in-text">Sign in to start your session</p>
        <br>
        <form method="post">
            <div class="form-group">
                <img class="profile-icons" src="../images/callicon.png" alt="User Icon">
                <input type="text" id="username" name="username" placeholder="USERNAME" required>
            </div>
            <div class="form-group">
                <img class="profile-icons" src="../images/key.svg" alt="Password Icon">
                <input type="password" id="password" name="inputpwd" placeholder="PASSWORD" required>
            </div>
            <div class="form-group">
                <center>
                    <input type="submit" name="login" class="btn-primary" value="Login">
                </center>
            </div>
        </form>
        <div>
            <a href="password-recovery.php" class="signup-link">Forgot your password?</a>
        </div>
    </div>
</body>
</html>
