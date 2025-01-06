<?php
session_start();
include "config.php";
include "getCart.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['total_price'], $_POST['payment_method'])) {
    $order_total = floatval($_POST['total_price']);
    $order_status = 'Pending';
    $payment_method = $_POST['payment_method'];
    $email = $_SESSION['email'];

    try {
        // Get user ID
        $stmt = $conn->prepare("SELECT user_id FROM signup WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $userResult = $stmt->get_result();

        if ($userResult->num_rows > 0) {
            $user = $userResult->fetch_assoc();
            $carts = getCart($conn);

            if (!empty($carts)) {
                // Insert order - Note the correct bind_param types
                $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total_amount, order_status, payment_method) VALUES (?, NOW(), ?, ?, ?)");
                $stmt->bind_param("idss", $user['user_id'], $order_total, $order_status, $payment_method);

                if ($stmt->execute()) {
                    $order_id = $stmt->insert_id;

                    // Insert order details
                    foreach ($carts as $cart) {
                        $detailStmt = $conn->prepare("INSERT INTO orderdetails (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
                        $detailStmt->bind_param("iiid", $order_id, $cart['book_id'], $cart['quantity'], $cart['price']);
                        
                        if (!$detailStmt->execute()) {
                            throw new Exception("Failed to insert order details for book ID: " . $cart['book_id']);
                        }
                    }

                    clearCart($conn);
                    header("Location: orderConfirmation.php");
                    exit();
                } else {
                    throw new Exception("Failed to create order");
                }
            } else {
                echo "Cart is empty.";
            }
        } else {
            echo "User not found.";
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "Error processing order: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}

function clearCart($con) {
    $email = $_SESSION['email'];
    $stmt = $con->prepare("DELETE FROM cart_items WHERE user_id = (SELECT user_id FROM signup WHERE email = ?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
}
?>