<?php
session_start();
include "config.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

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

$stmt = $conn->prepare("
    SELECT o.order_id, o.order_date, o.total_amount, o.order_status,
           GROUP_CONCAT(b.title SEPARATOR ', ') as books,
           GROUP_CONCAT(od.quantity SEPARATOR ', ') as quantities
    FROM orders o
    LEFT JOIN orderdetails od ON o.order_id = od.order_id
    LEFT JOIN books b ON od.book_id = b.book_id
    WHERE o.user_id = ?
    GROUP BY o.order_id
    ORDER BY o.order_date DESC");
    
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
        h1 {
            text-align: center;
            color: #FFFFFF;
        }
        table {
            margin: 0 auto;
            width: 80%;
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
        .books-cell {
            max-width: 300px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php">Back</a>
    </div>
    <h1>MY ORDERS</h1>
    <div class="orders-container">
        <table>
            <thead>
                <tr>
                    <th>Order Date</th>
                    <th>Books</th>
                    <th>Total Amount</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($order = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$order['order_date']}</td>
                                <td class='books-cell'>{$order['books']}</td>
                                <td>₹{$order['total_amount']}</td>
                                <td>{$order['order_status']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>