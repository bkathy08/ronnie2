<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php'); // Redirect to login if not logged in
    exit();
}

// Get user details from session
$user = $_SESSION['user'];

// Include the QR code library
include('phpqrcode/qrlib.php');

// Create a QR code based on user information, like the phone number or balance
$qrData = 'Phone Number: ' . $user['phone_number'] . '\nBalance: ₱' . number_format($user['balance'], 2);
$qrFile = 'qrcode.png';

// Generate the QR code and save it as an image file
QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash - Home</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-logo">
            <img src="logo.png" alt="GCash Logo"> <!-- Add your logo here -->
        </div>
        <div class="navbar-links">
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['firstname']); ?>!</h1>
        <div class="balance-info">
            <h2>Your Balance</h2>
            <p class="balance">₱<?php echo number_format($user['balance'], 2); ?></p>
        </div>

        <!-- QR Code Section -->
        <div class="qr-code">
            <h2>Your QR Code</h2>
            <p>Scan this QR code for your details</p>
            <img src="<?php echo $qrFile; ?>" alt="QR Code" class="qr-img">
        </div>

        <div class="actions">
            <div class="action-item">
                <a href="send_money.php" class="action-btn">Send Money</a>
            </div>
            <div class="action-item">
                <a href="pay_bills.php" class="action-btn">Pay Bills</a>
            </div>
            <div class="action-item">
                <a href="cash_in.php" class="action-btn">Cash In</a>
            </div>
        </div>

        <div class="footer">
            <p>GCash © 2024</p>
        </div>
    </div>
</body>
</html>
