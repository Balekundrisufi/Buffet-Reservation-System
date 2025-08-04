<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Edit Admin</title>
    <style>
        body {
            font-family: "DM Serif Display", serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #4a90e2;
            color: white;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        h1 {
            color: white;
        }
        h2 {
            margin-bottom: 1rem;
	    color:black;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 0.5rem 0;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #4a90e2;
            outline: none;
        }
        button {
            padding: 0.75rem;
            background-color: #4a90e2;
            border: none;
            color: white;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #357abd;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Admin</h1>
    </header>

    <div class="container">
        <?php
        include_once('includes/config.php');

        if (isset($_GET['id'])) {
            $admin_id = mysqli_real_escape_string($con_login, $_GET['id']);

            $query = "SELECT * FROM admins WHERE admin_id='$admin_id'";
            $result = mysqli_query($con_login, $query);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                ?>
                <section id="edit_admin_form">
                    <h2>Edit Admin Details</h2>
                    <form action="process_edit_admin.php" method="post">
                        <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($row['admin_id']); ?>">
                        
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                        
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                        
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                        
                        <button type="submit">Update Admin</button>
                    </form>
                </section>
                <?php
            } else {
                echo "<p>Admin not found.</p>";
            }
        }

        mysqli_close($con_login);
        ?>
    </div>
</body>
</html>
