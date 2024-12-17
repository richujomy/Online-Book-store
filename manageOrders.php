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
