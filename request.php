<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['customer_number'])) {
    header("Location: login.php");
    exit();
}

$customer_number = $_SESSION['customer_number'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_description = $_POST['item_description'];
    $weight = $_POST['weight'];
    $pickup_address = $_POST['pickup_address'];
    $pickup_suburb = $_POST['pickup_suburb'];
    $pickup_date = $_POST['pickup_date'];
    $pickup_time = $_POST['pickup_time'];
    $receiver_name = $_POST['receiver_name'];
    $delivery_address = $_POST['delivery_address'];
    $delivery_suburb = $_POST['delivery_suburb'];
    $delivery_state = $_POST['delivery_state'];

    // Input validation
    $current_time = strtotime("now");
    $pickup_datetime = strtotime("$pickup_date $pickup_time");

    if (empty($item_description) || empty($weight) || empty($pickup_address) || empty($pickup_suburb) || empty($pickup_date) || empty($pickup_time) || empty($receiver_name) || empty($delivery_address) || empty($delivery_suburb) || empty($delivery_state)) {
        $error_message = "All fields are required.";
    } elseif ($weight < 2 || $weight > 20) {
        $error_message = "Weight must be between 2 and 20 kg.";
    } elseif ($pickup_datetime < $current_time + 86400) {
        $error_message = "Pickup date must be at least 24 hours from now.";
    } elseif ($pickup_time < "08:00" || $pickup_time > "20:00") {
        $error_message = "Pickup time must be between 8:00 AM and 8:00 PM.";
    } else {
        // Calculate cost
        $cost = 20 + ($weight > 2 ? ($weight - 2) * 3 : 0);

        // Insert request into the database
        $insert_query = "INSERT INTO requests (customer_number, item_description, weight, pickup_address, pickup_suburb, pickup_date, pickup_time, receiver_name, delivery_address, delivery_suburb, delivery_state) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("isissssssss", $customer_number, $item_description, $weight, $pickup_address, $pickup_suburb, $pickup_date, $pickup_time, $receiver_name, $delivery_address, $delivery_suburb, $delivery_state);

        if ($stmt->execute()) {
            $request_number = $stmt->insert_id;
            $success_message = "Thank you! Your request number is $request_number. The cost is $$cost. We will pick-up the item at $pickup_time on $pickup_date.";

            // Retrieve customer info for email
            $customer_query = "SELECT name, email FROM customers WHERE customer_number = ?";
            $stmt = $conn->prepare($customer_query);
            $stmt->bind_param("i", $customer_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $customer = $result->fetch_assoc();

            // Send confirmation email
            $to = $customer['email'];
            $subject = "Shipping request with ShipOnline";
            $message = "Dear " . $customer['name'] . ",\n\nThank you for using ShipOnline! Your request number is $request_number. The cost is $$cost. We will pick-up the item at $pickup_time on $pickup_date.";
            $headers = "From: anuragsuri11@gmail.com";
            $is_email_send = mail($to, $subject, $message, $headers);
            // $is_email_send = mail($to, $subject, $message, $headers, "-r 1234567@student.swin.edu.au"); 
            if($is_email_send){
                $success_mail_message = "Email successfully sent with shipping details";
            } else {
                $error_mail_message = "Failed to send email.";
            }

        } else {
            $error_message = "There was an error processing your request. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ShipOnline Request Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFCC;
            margin: 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        form {
            background-color: #FFFF99;
            padding: 20px;
            border: 1px solid #ccc;
            width: 600px;
            margin: 0 auto;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input[type="text"], input[type="number"], input[type="date"], input[type="time"], select {
            width: 70%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            margin-top: 20px;
        }

        .message p {
            color: red;
        }

        .message.success p {
            color: green;
        }

        h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        fieldset {
            margin-bottom: 20px;
            padding: 10px;
            border: 2px solid #ccc;
        }

        legend {
            font-weight: bold;
            padding: 0 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>ShipOnline System Request Page</h1>
    <form id = "requestForm" method="POST" action="">
        <!-- Item Information -->
        <fieldset>
            <legend>Item Information</legend>
            <label>
                Description:
                <input type="text" name="item_description" required>
            </label>

            <label>
                Weight (kg):
                <input type="number" placeholder = "Select Weight" name="weight" min="2" max="20" required>
            </label>
        </fieldset>

        <!-- Pick-up Information -->
        <fieldset>
            <legend>Pick-up Information</legend>
            <label>
                Address:
                <input type="text" name="pickup_address" required>
            </label>

            <label>
                Suburb:
                <input type="text" name="pickup_suburb" required>
            </label>

            <label>
                Preferred Date:
                <input type="date" name="pickup_date" required>
            </label>

            <label>
                Preferred Time:
                <input type="time" name="pickup_time" min="08:00" max="20:00" 
               title="Please select a time between 08:00 and 20:00">
            </label> 
        </fieldset>

        <!-- Delivery Information -->
        <fieldset>
            <legend>Delivery Information</legend>
            <label>
                Receiver Name:
                <input type="text" name="receiver_name" required>
            </label>

            <label>
                Address:
                <input type="text" name="delivery_address" required>
            </label>

            <label>
                Suburb:
                <input type="text" name="delivery_suburb" required>
            </label>

            <label>
                State:
                <select name="delivery_state" required>
                    <option value="VIC">VIC</option>
                    <option value="NSW">NSW</option>
                    <option value="QLD">QLD</option>
                    <option value="SA">SA</option>
                    <option value="WA">WA</option>
                    <option value="TAS">TAS</option>
                    <option value="ACT">ACT</option>
                    <option value="NT">NT</option>
                </select>
            </label>
        </fieldset>

        <input type="submit" value="Request">
    </form>
    <div class="message">
        <?php 
        if (isset($success_message)) { echo "<p style='color:green;'>$success_message</p>"; }
        if (isset($success_mail_message)) { echo "<p style='color:green;'>$success_mail_message</p>"; }
        if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; }
        if (isset($error_mail_message)) { echo "<p style='color:green;'>$error_mail_message</p>"; }
        ?>
    </div>
    <!-- <script>
        document.getElementById('requestForm').addEventListener('submit', function(event) {
            var preferredTime = document.getElementById('preferredTime');
            var selectedTime = preferredTime.value;

            // Define min and max time limits
            var minTime = "08:00";
            var maxTime = "20:00";

            // Check if the time is within the allowed range
            if (selectedTime < minTime || selectedTime > maxTime) {
                // Prevent form submission
                event.preventDefault();

                // Display custom error message
                alert("Please select a time between 08:00 and 20:00.");
                preferredTime.setCustomValidity("Please select a time between 08:00 and 20:00.");
            } else {
                // Clear the custom validity message if input is valid
                preferredTime.setCustomValidity("");
            }
        });
    </script> -->
</body>
</html>