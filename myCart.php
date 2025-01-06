<?php
// myCart.php
include "config.php";
include "getCart.php";

session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user_id from the database
$userIdQuery = "SELECT user_id FROM signup WHERE email = ?";
$userIdStmt = $conn->prepare($userIdQuery);
$userIdStmt->bind_param('s', $email);
$userIdStmt->execute();
$userIdResult = $userIdStmt->get_result();

if ($userIdRow = $userIdResult->fetch_assoc()) {
    $userId = $userIdRow['user_id'];
} else {
    // If user not found, log them out
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle item removal
if (isset($_GET['remove_item'])) {
    $itemId = intval($_GET['remove_item']);

    // First get the quantity that was in cart for this item
    $quantityQuery = "SELECT quantity, book_id FROM cart_items WHERE book_id = ? AND user_id = ?";
    $quantityStmt = $conn->prepare($quantityQuery);
    $quantityStmt->bind_param('ii', $itemId, $userId);
    $quantityStmt->execute();
    $quantityResult = $quantityStmt->get_result();

    if ($row = $quantityResult->fetch_assoc()) {
        $quantityToRestore = $row['quantity'];
        $bookId = $row['book_id'];

        // Start transaction
        $conn->begin_transaction();

        try {
            // Delete the item from cart
            $deleteQuery = "DELETE FROM cart_items WHERE book_id = ? AND user_id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param('ii', $itemId, $userId);

            // Restore the quantity back to books table
            $restoreQuery = "UPDATE books SET quantity = quantity + ? WHERE book_id = ?";
            $restoreStmt = $conn->prepare($restoreQuery);
            $restoreStmt->bind_param('ii', $quantityToRestore, $bookId);

            // Execute both queries
            $deleteStmt->execute();
            $restoreStmt->execute();

            // Commit transaction
            $conn->commit();

            header("Location: myCart.php");
            exit();
        } catch (Exception $e) {
            // If there's an error, rollback changes
            $conn->rollback();
            echo "Error removing item from cart: " . $e->getMessage();
        }
    }
}

$carts = getCart($conn);
$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Cart</title>
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
        h3 {
            color: #FFFFFF;
            text-align: center;
        }
        table {
            margin: 0 auto;
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
        img {
            border-radius: 5px;
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
        .btn-buy {
            height: 40px;
            width: 150px;
            border-radius: 50px;
            display: block;
            margin: 20px auto;
            background-color: #007BFF;
            color: white;
            text-align: center;
            line-height: 40px;
            text-decoration: none;
        }
        .btn-buy:hover {
            background-color: #0056b3;
        }
        .delete-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php">Back</a>
    </div>

    <h1>Your Shopping Cart</h1>

    <?php if (empty($carts)): ?>
        <h3 class="empty-cart">Your cart is empty.</h3>
    <?php else: ?>
        <table>
            <tr>
                <th></th>
                <th>Book Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php foreach($carts as $cart): ?>
                <tr>
                    <td><img width="100" src="./img/<?= htmlspecialchars($cart['cover']) ?>"></td>
                    <td><?= htmlspecialchars($cart['title']) ?></td>
                    <td>₹<?= number_format($cart['price'], 2) ?></td>
                    <td><?= htmlspecialchars($cart['quantity']) ?></td>
                    <td>
                        <a href="myCart.php?remove_item=<?= $cart['book_id'] ?>" 
                           class="delete-button"
                           onclick="return confirm('Are you sure you want to remove this item?');">
                            Remove
                        </a>
                    </td>
                </tr>
                <?php $totalPrice += $cart['price'] * $cart['quantity']; ?>
            <?php endforeach; ?>
        </table>
        <h3>Total Price: ₹<?= number_format($totalPrice, 2) ?></h3>
        <a href="payment.php" class="btn-buy">Buy Now</a>
    <?php endif; ?>
</body>
</html>
