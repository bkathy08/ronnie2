<?php
// Include the connection file
include('database/connection.php');

// Check if form is submitted
if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone_number'], $_POST['password'], $_POST['mpin'])) {
    // Sanitize user input using real_escape_string
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $role = "client";  // Default role for new users
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Encrypt password
    $mpin = password_hash($_POST['mpin'], PASSWORD_BCRYPT);  // Encrypt MPIN
    $balance = 0.00;  // Initial balance is set to 0.00

    // Prepare a query to check if the email already exists in the database
    $check_sql = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('s', $email);  // "s" for string type
    $stmt->execute();
    $stmt->store_result(); // Store the result to get row count

    if ($stmt->num_rows > 0) {
        // If email exists, redirect with an error message
        header("Location: register.php?message=" . urlencode("Email is already taken!"));
        exit();
    } else {
        // Prepare the query to insert new user data
        $addusersql = "INSERT INTO users (firstname, lastname, phone_number, email, password, mpin, role, balance) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($addusersql);
        $stmt->bind_param('sssssssd', $firstname, $lastname, $phone_number, $email, $password, $mpin, $role, $balance);

        if ($stmt->execute()) {
            // Registration successful, redirect to login page with success message
            header("Location: index.php?message_success=" . urlencode("Registration successful!"));
            exit();
        } else {
            // Error during insertion
            echo "Error: " . $stmt->error;
            exit();
        }
    }
} else {
    // Redirect back if the form was not submitted properly
    header("Location: register.php?message=" . urlencode("Please fill out the form correctly."));
    exit();
}

?>