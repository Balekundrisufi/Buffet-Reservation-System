<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <title>Admin Details</title>
    <style>
        body {
            font-family: "DM Serif Display", serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #3a8dff;
            color: white;
            padding: 1rem;
            text-align: center;
			margin-bottom:10px;
        }
        .container {
            width: 80%;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
		
		.back_button{
			border-radius:10px;
			border-style:solid;
			background-color:yellow;
			color:black;
			padding:10px;
			text-decoration:none;
			margin-left:6.1%;
			font-weight:bolder;
			transition:background-color 0.3s;
				
		}
		.back_button:hover{
			color:white;
			background-color:blue;
		}
		
        h2 {
            color: #3a8dff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3a8dff;
            color: white;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #218838;
        }
        .button-danger {
            background-color: #dc3545;
        }
        .button-danger:hover {
            background-color: #c82333;
        }
        .actions a {
            margin: 0 5px;
            text-decoration: none;
            color:black;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Details</h1>
    </header>
	<a href="admin_dash.php" class="back_button"><span style="font-size:25px; vertical-align:sub;">&#129184;  </span>Back to dash</a>
    <div class="container">
        <section id="admin_list">
            <h2>Admin List</h2>
            <a href="add_admin.php" class="button">Add New Admin</a>
            <?php
            // Database connection
            include_once('includes/config.php');

            // Prepare and execute the query
            $query = "SELECT * FROM admins";
            if ($result = mysqli_query($con_login, $query)) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<table>
                            <tr>
                                <th>Admin ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['admin_id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td class='actions'>
                                    <a href='edit_admin.php?id={$row['admin_id']}'>Edit</a> | 
                                    <a href='process_delete_admin.php?id={$row['admin_id']}' class='button button-danger' onclick='return confirm(\"Are you sure you want to delete this admin?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No admin details available.</p>";
                }
            } else {
                echo "<p>Error executing query: " . mysqli_error($con_login) . "</p>";
            }

            mysqli_close($con_login);
            ?>
        </section>
    </div>
</body>
</html>
