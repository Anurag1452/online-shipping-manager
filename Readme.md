# ShipOnline System

## Overview

The **ShipOnline System** is a web-based application designed to manage shipping requests for customers. The system allows users to register, login, make shipping requests, and view those requests through an administration interface. This project demonstrates the use of PHP for server-side scripting, MySQL for database management, and basic HTML/CSS for the user interface.

## Features

### 1. **Customer Registration**
   - **Purpose:** Allows new customers to register to the system.
   - **Functionalities:**
     - Customers provide their name, password, email, and contact phone number.
     - The system checks for input completeness, matching passwords, and email uniqueness.
     - A unique customer number is generated for each customer upon successful registration.
     - Confirmation message displays the customer’s name and generated customer number.
   - **File:** `register.php`

### 2. **Customer Login and Shipping Request**
   - **Purpose:** Allows registered customers to log in and submit a shipping request.
   - **Functionalities:**
     - **Login:**
       - Customers log in using their customer number and password.
       - Upon successful login, they are redirected to the request page.
     - **Request:**
       - Customers submit details about the item to be shipped, pick-up information, and delivery details.
       - The system checks for input completeness, valid weight, and appropriate pick-up date and time.
       - A request number and date are generated, and the cost is calculated based on the item's weight.
       - A confirmation message with the request number, cost, and pick-up details is displayed.
   - **Files:** `login.php`, `request.php`

### 3. **Administration Interface**
   - **Purpose:** Allows the system administrator to view requests based on specific dates.
   - **Functionalities:**
     - The administrator can view requests based on either the request date or pick-up date.
     - The system displays the relevant requests in a sorted table.
     - It also shows the total number of requests and, depending on the selection, the total revenue or total weight.
   - **File:** `admin.php`

## Technologies Used

1. **PHP:** Server-side scripting language used for processing forms, handling database queries, and generating dynamic content.
2. **MySQL:** Relational database management system used to store customer and request data.
3. **HTML/CSS:** Front-end technologies used to create and style the user interface.
4. **Mail Functionality:** PHP’s `mail()` function is used to send confirmation emails to customers upon successful request submission.

## Database Schema

### 1. **Customers Table**
   - **Columns:**
     - `customer_number` (Primary Key)
     - `name`
     - `password`
     - `email`
     - `contact_phone`

### 2. **Requests Table**
   - **Columns:**
     - `request_number` (Primary Key or combined with `customer_number`)
     - `customer_number` (Foreign Key)
     - `request_date`
     - `item_description`
     - `weight`
     - `pickup_address`
     - `pickup_suburb`
     - `preferred_pickup_date`
     - `pickup_time`
     - `receiver_name`
     - `delivery_address`
     - `delivery_suburb`
     - `delivery_state`

## Installation and Setup

### 1. **Prerequisites**
   - A web server with PHP support (e.g., Apache, Nginx).
   - MySQL database server.
   - Basic knowledge of using a terminal/command prompt.

### 2. **Setting Up the Database**
   - Create a new MySQL database.
   - Run the following SQL scripts to create the necessary tables:

```sql
CREATE TABLE customers (
    customer_number INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    contact_phone VARCHAR(15) NOT NULL
);

CREATE TABLE requests (
    request_number INT AUTO_INCREMENT PRIMARY KEY,
    customer_number INT,
    request_date DATETIME,
    item_description TEXT,
    weight INT,
    pickup_address TEXT,
    pickup_suburb VARCHAR(255),
    preferred_pickup_date DATETIME,
    pickup_time TIME,
    receiver_name VARCHAR(255),
    delivery_address TEXT,
    delivery_suburb VARCHAR(255),
    delivery_state VARCHAR(50),
    FOREIGN KEY (customer_number) REFERENCES customers(customer_number)
);
```

### 3. **Configuring the PHP Files**
   - Open each PHP file (`register.php`, `login.php`, `request.php`, `admin.php`).
   - Update the database connection details with your MySQL credentials:

```php
$conn = new mysqli("localhost", "username", "password", "database");
```

### 4. **Deploying the Application**
   - Place the project files in the web root directory of your server (e.g., `htdocs` for XAMPP, `www` for WAMP).
   - Ensure the server is running, and then access the system by navigating to `http://localhost/your_project_directory/shiponline.php` in your web browser.

### 5. **Using the System**
   - **Register:** Start by registering a new customer using the registration form at `register.php`.
   - **Login:** Use the customer number and password to log in via `login.php`.
   - **Request:** After logging in, submit a shipping request through `request.php` and view the confirmation message.
   - **Admin:** Access the admin page at `admin.php` to view requests filtered by request date or pick-up date.

## Conclusion

The ShipOnline System is a web-based application built using PHP and MySQL that facilitates the registration, login, and management of shipping requests for customers. The project demonstrates key web development concepts, including form handling, database interaction, and basic validation. It also provides an administrative interface for managing shipping requests, which can be expanded further with additional features. This project serves as a solid foundation for understanding web application development with embedded PHP and MySQL, and offers opportunities for enhancement in areas such as security, user authentication, and UI/UX design.
