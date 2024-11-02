<?php
session_start();
include "config.php";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Ensure required fields are set
if (isset($_POST['book_id'], $_POST['price'], $_POST['payment_method'])) {
    $book_id = intval($_POST['book_id']); // Typecast to int for security
    $order_total = floatval($_POST['price']); // Ensure price is a float
    $order_status = 'Pending';
    $payment_method = $_POST['payment_method'];

    // Fetch user ID based on the email
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT user_id FROM signup WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();

        // Simulate payment processing here (this is where you would integrate real payment processing)

        // Insert order into the database
     // Insert order into the database
$stmt = $conn->prepare("INSERT INTO orders (user_id, book_id, order_date, total_amount, order_status, payment_method) VALUES (?, ?, NOW(), ?, ?, ?)");
$stmt->bind_param("iisds", $user['user_id'], $book_id, $order_total, $order_status, $payment_method);


        if ($stmt->execute()) {
            // Redirect to confirmation page
            header("Location: orderConfirmation.php");
            exit();
        } else {
            echo "Failed to create order. Please try again.";
        }
    } else {
        echo "User not found.";
    }
} else {
    echo "Invalid request.";
}
?>
