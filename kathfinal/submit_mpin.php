<?php
// Include database connection file
include('database/connection.php');
// Start a session to manage user data
session_start();

if (isset($_POST['login'])) {
    // Sanitize the phone number to prevent SQL injection
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    // Get password from the form (note: not yet encrypted here)
    $password = $_POST['password'];

    // SQL query to select user data from database based on the phone number
    $sql_phone_number = "SELECT * FROM users WHERE phone_number='$phone_number'";
    // Execute query
    $result = $conn->query($sql_phone_number);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
        // Fetch the associated user data
        $row = $result->fetch_assoc();
        // Verify password against stored hash password
        if (password_verify($password, $row['password'])) {
            // Set session variables for phone number and role
            $_SESSION['phone_number'] = $phone_number;
            $_SESSION['role'] = $row['role'];

            // Redirect to home.php after successful login
            header("Location: home.php?phone_number=" . urlencode($phone_number));
            exit(); // Ensure no further code is executed after redirect
        } else {
            // If the password is incorrect, show an error message and redirect to login page
            header("Location: index.php?incorrect");
            exit();
        }
    } else {
        // If no user found with the provided phone number, redirect to login page with error message
        header("Location: index.php?incorrect_phone_number");
        exit();
    }
}
?>
