<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
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
        }
        h2 {
            color: #000000; /* Black heading */
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #000000; /* Black button background */
            border-color: #000000; /* Black button border */
        }
        .btn-primary:hover {
            background-color: #404040; /* Dark gray button hover background */
            border-color: #404040; /* Dark gray button hover border */
        }
        .table {
            background-color: #ffffff; /* White table background */
        }
        th, td {
            color: #000000; /* Black table text */
        }
    </style>
</head>
<body>
    <div class="container my-6">
        <h2>List of Clients</h2>
        <!-- Logout button -->
        <a class="btn btn-primary" href="/MYSHOP/login.php" role="button">Logout</a>
        <a class="btn btn-primary" href="/MYSHOP/create.php" role="button">New Clients</a>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Password</th>
                    <th>Created_At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

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

// Registration Logic
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

// Display Clients
$sql = "SELECT * FROM clients";
$result = $connection->query($sql);

if (!$result) {
    die("Invalid query: " . $connection->error);
}

while ($row = $result->fetch_assoc()) {
    echo "
    <tr>
        <td>" . $row['id'] . "</td>
        <td>" . $row['name'] . "</td>
        <td>" . $row['email'] . "</td>
        <td>" . $row['phone'] . "</td>
        <td>" . $row['address'] . "</td>
        <td>" . $row['password'] . "</td>
        <td>" . $row['created_at'] . "</td>
        <td>
            <a class='btn btn-primary btn-sm' href='/MYSHOP/edit.php?id=" . $row['id'] . "'>Edit</a>
            <a class='btn btn-danger btn-sm' href='/MYSHOP/delete.php?id=" . $row['id'] . "'>Delete</a>
        </td>
    </tr>";
}

$connection->close();
?>



            </tbody>
        </table>
    </div>
</body>
</html>
