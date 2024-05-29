<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "clients";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Echo SQL query for debugging
    $sql = "SELECT * FROM clients WHERE email = '$email'";
    // echo "SQL Query: $sql<br>"; // Debugging line
    $result = $connection->query($sql);

    if ($result === false) {
        // Check for SQL errors
        die("SQL Error: " . $connection->error);
    }

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Echo retrieved password for debugging
        // echo "Retrieved Password: " . $user['password'] . "<br>"; // Debugging line
        if (password_verify($password, $user['password'])) {
            // Password correct, start session and store user info
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            // Redirect to dashboard or any other authenticated page
            header("Location: index.php");
            exit;
        } else {
            // Invalid password
            $errorMessage = "Invalid email or password. Please try again.";
            // echo "Invalid password entered<br>"; // Debugging line
        }
    } else {
        // User not found
        $errorMessage = "Invalid email or password. Please try again.";
        // echo "User not found<br>"; // Debugging line
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #ffffff; /* White background */
            color: #000000; /* Black text */
        }
        .container {
            background-color: #f2f2f2; /* Light gray container background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            width: 300px; /* Adjust width as needed */
        }
        h2 {
            color: #000000; /* Black heading */
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-primary {
            background-color: #000000; /* Black button background */
            border-color: #000000; /* Black button border */
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #404040; /* Dark gray button hover background */
            border-color: #404040; /* Dark gray button hover border */
        }
        .form-label {
            color: #000000; /* Black form label */
        }
        .form-control {
            background-color: #ffffff; /* White form control background */
            color: #000000; /* Black form control text */
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2>Login</h2>
        <form method="post" action="">
            <?php if(isset($errorMessage)) { ?>
                <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php } ?>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <div class="mt-3">
                <p class="text-center">Don't have an account? <a class="register-link" href="register.php">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>
