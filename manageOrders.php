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

// Handle order deletion
if (isset($_GET['delete_order_id'])) {
    $order_id = intval($_GET['delete_order_id']);
    
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    
    if ($stmt->execute()) {
        echo "Order deleted successfully.";
    } else {
        echo "Failed to delete order.";
    }
}

// Fetch orders from the database
$stmt = $conn->prepare("SELECT orders.order_id, orders.order_date, orders.total_amount, orders.order_status, signup.email 
                         FROM orders 
                         JOIN signup ON orders.user_id = signup.user_id");
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Order Management</h2>
<table border="1">
    <tr>
        <th>Order ID</th>
        <th>User Email</th>
        <th>Order Date</th>
        <th>Total Amount</th>
        <th>Order Status</th>
        <th>Actions</th>
    </tr>

    <?php while ($order = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $order['order_id']; ?></td>
        <td><?php echo $order['email']; ?></td>
        <td><?php echo $order['order_date']; ?></td>
        <td><?php echo $order['total_amount']; ?></td>
        <td>
            <form method="post" style="display:inline;">
                <select name="order_status">
                    <option value="Pending" <?= $order['order_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= $order['order_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="Cancelled" <?= $order['order_status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                <button type="submit" name="update_order">Update</button>
            </form>
        </td>
        <td>
            <a href="?delete_order_id=<?php echo $order['order_id']; ?>" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
