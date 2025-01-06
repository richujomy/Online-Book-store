<?php
session_start();
include "config.php";


// Handle order status update
if (isset($_POST['update_order'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['order_status'];
    
    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        echo "Order status updated successfully.";
    } else {
        echo "Failed to update order status.";
    }
}
// Fetch orders from the database
$stmt = $conn->prepare("SELECT orders.order_id, orders.order_date, orders.total_amount, orders.order_status, signup.username
                         FROM orders 
                         JOIN signup ON orders.user_id = signup.user_id");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage orders</title>
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

h2 {
    text-align: center;
    color: #444;
    margin-top: 20px;
}

/* Table Styles */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border-radius: 8px;
}

table th,
table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color:  #333;
    color: white;
    text-transform: uppercase;
    font-size: 14px;
}

table tr:hover {
    background-color: #f1f1f1;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Form Styles */
form {
    display: flex;
    align-items: center;
    gap: 10px;
}

select {
    padding: 5px;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 14px;
    outline: none;
}

button {
    padding: 6px 12px;
    background-color:  #333;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

button:hover {
    background-color: #0056b3;
}
.navbar {
            background-color: #333;
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
        <a href="admin.php">Back</a>
    </div>
<h2>Order Management</h2>
<table border="1">
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Order Date</th>
        <th>Total Amount</th>
        <th>Order Status</th>
    </tr>

    <?php while ($order = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $order['order_id']; ?></td>
        <td><?php echo $order['username']; ?></td>
        <td><?php echo $order['order_date']; ?></td>
        <td><?php echo $order['total_amount']; ?></td>
        <td>
            <form method="post" style="display:inline;">
                <select name="order_status">
                    <option value="Pending" <?= $order['order_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= $order['order_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                 
                </select>
                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                <button type="submit" name="update_order">Update</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

