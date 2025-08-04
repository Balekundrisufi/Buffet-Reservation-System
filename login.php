<?php
session_start();

// Include configuration file
include_once('admin/includes/config.php');

// Define variables and initialize with empty values
$username = $password = "";
$login_err = "";

// Check if the session contains the username and password from the registration page
if (isset($_SESSION['register_username']) && isset($_SESSION['register_password'])) {
    $username = $_SESSION['register_username'];
    $password = $_SESSION['register_password'];
    // Clear session variables after use
    unset($_SESSION['register_username']);
    unset($_SESSION['register_password']);
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Prepare a select statement
    $sql = "SELECT user_id, username, password ,email, phone_number FROM users WHERE username = ?";

    if ($stmt = mysqli_prepare($con_login, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password,$email,$phone_number);
                if (mysqli_stmt_fetch($stmt)) {
                   if (password_verify($password, $hashed_password)) {
						// Password is correct, so start a new session
						$_SESSION["loggedin"] = true;
						$_SESSION["id"] = $id;
						$_SESSION["username"] = $username;
						$_SESSION["email"] = $email; // Store email in session
						$_SESSION["phone_number"] = $phone_number; // Store phone number in session
						
						// Redirect user to welcome page
						header("location: index.php");
						exit;
					}
					 else {
                        // Display an error message if password is not valid
                        $login_err = "Invalid username or password.";
                    }
                }
            } else {
                // Display an error message if username doesn't exist
                $login_err = "Invalid username or password.";
            }
        } else {
            $login_err = "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($con_login);
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
    <title>Buffet Kart Menu</title>
    <style>
        body {
            background-image: linear-gradient(to right, red, black);
            padding-top: 100px;
            margin: 0;
            font-family: "DM Serif Display", serif;
        }

        .fix {
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            z-index: 1;
        }

        .top-logo {
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: lightblue;
        }

        .logo {
            width: 50px;
            height: 50px;
        }

        .brand-ani {
            font-size: 3rem;
            font-weight: bold;
            color: yellow;
            font-family: "DM Serif Display", serif;
            text-shadow: 2px 2px 4px #000000;
        }

        .brand-ani::before {
            content: 'B';
            animation: changetext 4s infinite;
        }

        @keyframes changetext {
            0%, 10% { content: 'B'; }
            11%, 20% { content: 'Bu'; }
            21%, 30% { content: 'Buf'; }
            31%, 40% { content: 'Buff'; }
            41%, 50% { content: 'Buffe'; }
            51%, 60% { content: 'Buffet'; }
            61%, 70% { content: 'BuffetK'; }
            71%, 80% { content: 'BuffetKa'; }
            81%, 90% { content: 'BuffetKar'; }
            91%, 100% { content: 'BuffetKart'; }
        }

        .header {
            display: flex;
            padding-left: 5%;
            justify-content: space-evenly;
            align-items: center;
            width: 100%;
            height: 50px;
            background-color: black;
            color: white;
        }

        a {
            color: white;
            font-weight: 800;
            text-decoration: none;
            font-size: 18px;
        }

        a:hover {
            color: yellow;
        }

        .home:hover {
            cursor: pointer;
        }
		
		.active {
            color: yellow;
        }

        .welcome {
            width: 500px;
            font-size: 70px;
            margin-bottom: 20px;
            color: gold;
        }

        .alert-danger {
            color: gold;
        }

        .wrapper {
            background-color: transparent;
            background-attachment: contain;
            padding: 0 30px;
            padding-bottom: 10px;
            margin: auto;
            margin-top: 50px;
            border-radius: 30px;
            border-color: yellow;
            border-style: solid;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 360px;
            text-align: center;
            position: relative;
        }

        .abs {
            top: 50px;
            left: 140px;
            width: 150px;
            height: 150px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

	.form-group2{
            margin-bottom: 10px;
	    text-align:center;
        }

        .profile-icons {
            width: 30px;
            height: 30px;
            display: block;
        }

        #username, #password {
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

        .signup-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

	p{
	    margin:0px;
	}

        .signup-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="fix">
        <div class="top-logo">
            <img class="logo" src="images/hand-fingers-crossed_10416112-removebg-preview.png" alt="logo">
            <p class="brand-ani"></p>
        </div>
        <div class="header">
            <div class="home" style="flex:1;"><a class="nav-link" href="home_page.html">HOME</a></div>
            <div class="home" style="flex:1;"><a class="nav-link" href="menu.html">MENUS</a></div>
            <div class="home" style="flex:1;"><a class="nav-link" href="contact.html">CONTACT US</a></div>
            <div class="home" style="flex:1;"><a class="nav-link" href="register.php">REGISTER</a></div>
            <div class="home" style="flex:1;"><a class="nav-link" href="login.php">LOGIN</a></div>
            <div class="home" style="flex:1;"><a class="nav-link" href="admin/">ADMIN</a></div>
        </div>
    </div>
    <center>
        <div class="welcome">
            WELCOME TO BUFFET KART
        </div>
    </center>
    <div class="wrapper">
        <img class="abs" src="images/signup_icon.png">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <img class="profile-icons" src="images/profile.svg">
                <input type="text" id="username" name="username" placeholder="USERNAME" required value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="form-group">
                <img class="profile-icons" src="images/key.svg">
                <input type="password" id="password" name="password" required placeholder="PASSWORD" value="<?php echo htmlspecialchars($password); ?>">
            </div>
            <div class="form-group">
			<center>
                <input type="submit" class="btn-primary" value="LOGIN">
			</center>
            </div>
            <div class="form-group">
                <p class="alert-danger"><?php echo $login_err; ?></p>
            </div>
            <div class="form-group2">
                <p>Don't have an account? <a class="signup-link" href="register.php">Sign up now</a></p>
            </div>
	    <div class="form-group2">
                <p>Forget password? <a class="signup-link" href="forgot_password.php">Forgot password</a></p>
            </div>
        </form>
    </div>
	<script>
    // JavaScript to set the active class based on the current page URL
    const currentLocation = window.location.href;
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        if (link.href === currentLocation) {
            link.classList.add('active');
        }
    });
</script>
</body>
</html>
