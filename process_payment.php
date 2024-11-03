<?php
session_start();
include "config.php";
include "getCart.php";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Ensure required fields are set
if (isset($_POST['total_price'], $_POST['payment_method'])) {
    $order_total = floatval($_POST['total_price']); // Ensure price is a float
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

        // Fetch all cart items for the logged-in user
        $carts = getCart($conn); // Reuse the getCart function

        if (!empty($carts)) {
            // Simulate payment processing
            // Insert each item in the cart as a new order
            foreach ($carts as $cart) {
                $book_id = $cart['book_id']; // Make sure this key exists in your cart items
                $order_amount = $cart['price'] * $cart['quantity'];

                $stmt = $conn->prepare("INSERT INTO orders (user_id, book_id, order_date, total_amount, order_status, payment_method) VALUES (?, ?, NOW(), ?, ?, ?)");
                $stmt->bind_param("iisds", $user['user_id'], $book_id, $order_amount, $order_status, $payment_method);

                if (!$stmt->execute()) {
                    echo "Failed to create order for book ID: $book_id. Please try again.";
                    exit();
                }
            }

            // Clear the cart after successful order processing (optional)
            clearCart($conn);

            // Redirect to confirmation page
            header("Location: orderConfirmation.php");
            exit();
        } else {
            echo "Cart is empty.";
        }
    } else {
        echo "User not found.";
    }
} else {
    echo "Invalid request.";
}

// Function to clear the cart (optional)
function clearCart($con) {
    $email = $_SESSION['email'];
    $stmt = $con->prepare("DELETE FROM cart_items WHERE user_id = (SELECT user_id FROM signup WHERE email = ?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
}
?>
