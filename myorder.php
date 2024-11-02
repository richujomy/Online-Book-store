<?php
session_start();
include "config.php";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch user ID based on the email
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM signup WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$userResult = $stmt->get_result();

if ($userResult->num_rows === 0) {
    echo "User not found.";
    exit();
}

$user = $userResult->fetch_assoc();
$user_id = $user['user_id'];

// Fetch orders for the logged-in user
$stmt = $conn->prepare("SELECT orders.order_id, orders.order_date, orders.total_amount, orders.order_status, books.title 
                        FROM orders 
                        JOIN books ON orders.book_id = books.book_id 
                        WHERE orders.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file here -->
</head>
<body>
    <div class="orders-container">
        <h1>My Orders</h1>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Book Title</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Order Status</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($order = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$order['order_id']}</td>
                            <td>{$order['title']}</td>
                            <td>{$order['order_date']}</td>
                            <td>\${$order['total_amount']}</td>
                            <td>{$order['order_status']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
