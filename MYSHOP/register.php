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
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    // Check if email already exists
    $check_sql = "SELECT * FROM clients WHERE email = ?";
    $check_stmt = $connection->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        $errorMessage = "This email is already registered. Please use a different email address.";
    } else {
        // Insert user into the database
        $insert_sql = "INSERT INTO clients (name, email, phone, address, password, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $insert_stmt = $connection->prepare($insert_sql);
        $insert_stmt->bind_param("sssss", $name, $email, $phone, $address, $password);

        if ($insert_stmt->execute()) {
            // Registration successful, redirect to login page or wherever needed
            header("Location: login.php");
            exit;
        } else {
            // Registration failed
            $errorMessage = "Registration failed. Please try again.";
        }
    }

    $check_stmt->close();
    $insert_stmt->close();
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
        <h2>Registration</h2>
        <form method="post" action="register.php">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <div class="mt-3">
                <p class="text-center">Already have an account? <a class="register-link" href="login.php">Login</a></p>
            </div>
        </form>
    </div>
</body>
</html>
