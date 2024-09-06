



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ShipOnline Administration Page</title>
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
            background-color: #FFCC99;
            padding: 20px;
            border: 1px solid #ccc;
            width: 400px;
            margin: 0 auto;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        select, input[type="submit"], input[type="radio"] {
            margin-top: 10px;
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

        table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        a {
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>ShipOnline System Administration Page</h1>
    <form method="POST" action="">
        <label>Date for Retrieve:</label>
        <select name="day">
            <?php for ($i = 1; $i <= 31; $i++) echo "<option value='$i'>$i</option>"; ?>
        </select>
        <select name="month">
            <?php for ($i = 1; $i <= 12; $i++) echo "<option value='$i'>$i</option>"; ?>
        </select>
        <select name="year">
            <?php for ($i = 2024; $i <= 2030; $i++) echo "<option value='$i'>$i</option>"; ?>
        </select>

        <label>Select Date Item for Retrieve:</label>
        <input type="radio" name="date_type" value="request_date" checked> Request Date
        <input type="radio" name="date_type" value="pickup_date"> Pick-up Date

        <input type="submit" value="Show">
    </form>

    <div class="message">
        <?php 
        if (isset($error_message)) { echo "<p>$error_message</p>"; }
        ?>
    </div>

    <p style="text-align: center;"><a href="shiponline.php">Home</a></p>
</body>
</html>



<?php
include('db_connection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $date_type = $_POST['date_type'];

    $selected_date = "$year-$month-$day";
    $total_requests = 0;
    $total_revenue_or_weight = 0;

    if ($date_type == "request_date") {
        $query = "SELECT customer_number, request_number, item_description, weight, pickup_suburb, pickup_date, delivery_suburb, delivery_state 
                  FROM requests 
                  WHERE DATE(request_date) = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $selected_date);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<table border='1'>
                <tr>
                    <th>Customer Number</th>
                    <th>Request Number</th>
                    <th>Item Description</th>
                    <th>Weight</th>
                    <th>Pick-up Suburb</th>
                    <th>Pick-up Date</th>
                    <th>Delivery Suburb</th>
                    <th>Delivery State</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            $total_requests++;
            $cost = 20 + ($row['weight'] > 2 ? ($row['weight'] - 2) * 3 : 0);
            $total_revenue_or_weight += $cost;

            echo "<tr>
                    <td>{$row['customer_number']}</td>
                    <td>{$row['request_number']}</td>
                    <td>{$row['item_description']}</td>
                    <td>{$row['weight']} kg</td>
                    <td>{$row['pickup_suburb']}</td>
                    <td>{$row['pickup_date']}</td>
                    <td>{$row['delivery_suburb']}</td>
                    <td>{$row['delivery_state']}</td>
                  </tr>";
        }
        echo "</table>";
        echo "<p>Total Requests: $total_requests</p>";
        echo "<p>Total Revenue: $$total_revenue_or_weight</p>";
    } elseif ($date_type == "pickup_date") {
        $query = "SELECT c.customer_number, c.name, c.contact_phone, r.request_number, r.item_description, r.weight, r.pickup_address, r.pickup_suburb, r.pickup_time, r.delivery_suburb, r.delivery_state 
                  FROM requests r 
                  JOIN customers c ON r.customer_number = c.customer_number 
                  WHERE DATE(r.pickup_date) = ?
                  ORDER BY r.pickup_suburb, r.delivery_state, r.delivery_suburb";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $selected_date);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<table border='1'>
                <tr>
                    <th>Customer Number</th>
                    <th>Customer Name</th>
                    <th>Contact Phone</th>
                    <th>Request Number</th>
                    <th>Item Description</th>
                    <th>Weight</th>
                    <th>Pickup Address</th>
                    <th>Pickup Suburb</th>
                    <th>Pickup Time</th>
                    <th>Delivery Suburb</th>
                    <th>Delivery State</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            $total_requests++;
            $total_revenue_or_weight += $row['weight'];

            echo "<tr>
                    <td>{$row['customer_number']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['contact_phone']}</td>
                    <td>{$row['request_number']}</td>
                    <td>{$row['item_description']}</td>
                    <td>{$row['weight']} kg</td>
                    <td>{$row['pickup_address']}</td>
                    <td>{$row['pickup_suburb']}</td>
                    <td>{$row['pickup_time']}</td>
                    <td>{$row['delivery_suburb']}</td>
                    <td>{$row['delivery_state']}</td>
                  </tr>";
        }
        echo "</table>";
        echo "<p>Total Requests: $total_requests</p>";
        echo "<p>Total Weight: $total_revenue_or_weight kg</p>";
    }
}
?>

