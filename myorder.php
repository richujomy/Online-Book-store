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
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins';
            background-color: #323643;
            margin: 0;
            padding: 0;
        }
        h1{
            text-align: center;
            color: #FFFFFF;
        }

        h3 {
            color: #333;
            margin-top: 20px;
            text-align: center;
            color: #FFFFFF;
        }

        table {
            margin: 0 auto 0 auto;
            width: 60%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
            color: #555;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
        .navbar {
            background-color: #28282B;
            color: #fff;
            padding: 30px;
            text-align: left;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
        }
        .navbar a:hover {
            background-color: #555;
        }

    </style>
</head>
<body>
<div class="navbar">
        <a href="home.php">Back</a>
    </div>
    </nav>
    <h1>MY ORDERS</h1>
    <div class="orders-container">
        <table>
            <thead>
                <tr>
                    <!-- <th>Order ID</th> -->
                    <th>Book Title</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($order = $result->fetch_assoc()) {
                        echo "<tr>
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
            </tbody>
        </table>
    </div>
</body>
</html>
