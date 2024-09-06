<?php
// Include the database connection file
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $contact_phone = $_POST['contact_phone'];

    // Validation
    if (empty($name) || empty($password) || empty($confirm_password) || empty($email) || empty($contact_phone)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if the email already exists
        $query = "SELECT * FROM customers WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email address is already registered.";
        } else {

            // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new customer into the database
            $insert_query = "INSERT INTO customers (name, password, email, contact_phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssss", $name, $password, $email, $contact_phone);

            if ($stmt->execute()) {
                $customer_number = $stmt->insert_id;
                $success_message = "Dear $name, you are successfully registered into ShipOnline, and your customer number is $customer_number, which will be used to get into the system.";
            } else {
                $error_message = "There was an error registering your account. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ShipOnline System Registration Page</title>
    <style>
        body {
            background-color: #FFFFE0;
            font-family: Arial, sans-serif;
            color: #000;
        }
        h1 {
            text-align: center;
            margin-top: 50px;
        }
        form {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
            background-color: #FFFACD;
        }
        label, input {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>ShipOnline System Registration Page</h1>
    <form method="POST" action="">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <label>Email Address:</label>
        <input type="text" name="email" required>

        <label>Contact Phone:</label>
        <input type="text" name="contact_phone" required>

        <input type="submit" value="Register">
    </form>

    <div class="message">
        <?php 
        if (isset($error_message)) {
            echo "<p style='color:red;'>$error_message</p>";
        } elseif (isset($success_message)) {
            echo "<p style='color:green;'>$success_message</p>";
        }
        ?>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <a href="shiponline.php">Home</a>
    </div>
</body>
</html>
