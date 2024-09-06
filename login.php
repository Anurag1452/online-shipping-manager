<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_number = $_POST['customer_number'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($customer_number) || empty($password)) {
        $error_message = "Both fields are required.";
    } else {
        // Check if the customer exists
        $query = "SELECT * FROM customers WHERE customer_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $customer_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $customer = $result->fetch_assoc();
            // Verify the password
            // if (password_verify($password, $customer['password'])) {
            if (($password === $customer['password'])) {
                $_SESSION['customer_number'] = $customer_number; // Store customer number in session
                header("Location: request.php?customer_number=$customer_number");
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "Customer number not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ShipOnline Login</title>
    <style>
        body {
            background-color: #FFFFCC;
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #333333;
        }

        form {
            background-color: #FFFFE0;
            border: 1px solid #DDDDDD;
            padding: 20px;
            width: 300px;
            margin: 100px auto;
            margin-bottom: 0;
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 80%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #CCCCCC;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        a {
            display: block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>ShipOnline System Login Page</h1>
    <form method="POST" action="">
        <label>Customer Number:</label>
        <input type="text" name="customer_number" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Log In">
    </form>
    <p style="text-align: center;"><a href="shiponline.php">Home</a></p>
    <div class="message">
        <?php if (isset($error_message)) { echo "<p>$error_message</p>"; } ?>
    </div>
</body>
</html>
