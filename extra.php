<!DOCTYPE html>
<html>
<head>
    <title>ShipOnline Request Page</title>
    <style>
        form {
    background-color: #FFCC99;
    padding: 20px;
    border: 1px solid #ccc;
    width: 800px; 
    margin: 0 auto;
}

fieldset {
    border: none;
    margin-bottom: 20px;
}

legend {
    font-weight: bold;
    padding-bottom: 10px;
    display: block;
}

.form-row {
    display: flex;
    flex-wrap: wrap; 
    justify-content: space-between;
    margin-bottom: 15px;
}

label, input, select {
    width: calc(33% - 10px); 
    margin-right: 10px;
}

input[type="text"], input[type="number"], input[type="date"], input[type="time"], select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%; /* Ensures inputs take full width in their column */
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    margin-top: 20px;
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

    </style>
</head>
<body>
    <h1>ShipOnline System Request Page</h1>
    <form method="POST" action="">
        <fieldset>
            <legend>Item Information</legend>
            <div class="form-row">
                <label>Description:</label>
                <input type="text" name="item_description" required>

                <label>Weight (kg):</label>
                <input type="number" name="weight" min="2" max="20" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>Pick-up Information</legend>
            <div class="form-row">
                <label>Address:</label>
                <input type="text" name="pickup_address" required>

                <label>Suburb:</label>
                <input type="text" name="pickup_suburb" required>

                <label>Preferred Date:</label>
                <input type="date" name="pickup_date" required>

                <label>Preferred Time:</label>
                <input type="time" name="pickup_time" min="08:00" max="20:00" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>Delivery Information</legend>
            <div class="form-row">
                <label>Receiver Name:</label>
                <input type="text" name="receiver_name" required>

                <label>Address:</label>
                <input type="text" name="delivery_address" required>

                <label>Suburb:</label>
                <input type="text" name="delivery_suburb" required>

                <label>State:</label>
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
            </div>
        </fieldset>

        <input type="submit" value="Submit Request">
    </form>

    <div class="message">
        <?php 
        if (isset($success_message)) { echo "<p style='color:green;'>$success_message</p>"; }
        if (isset($success_mail_message)) { echo "<p style='color:green;'>$success_mail_message</p>"; }
        if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; }
        if (isset($error_mail_message)) { echo "<p style='color:green;'>$error_mail_message</p>"; }
        ?>
    </div>
</body>
</html>
