<?php
session_start();
include "config.php";


// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include "getCart.php";
$carts = getCart($conn);
$totalPrice = 0; // For displaying cart total

if (empty($carts)) {
    echo "Your cart is empty.";
    exit;
}

foreach ($carts as $cart) {
    $totalPrice += $cart['price'] * $cart['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Payment</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins';
            background-color: #323643;
            color: #333;
            margin: 0;
        }
        h1 {
            text-align: center;
        }
        .payment-container {
            max-width: 600px;
            margin: 25px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .book-details {
            text-align: center;
        }
        button {
            padding: 10px 15px;
            font-size: 16px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="home.php">Back</a>
</div>
<div class="payment-container">
    <h1>Proceed to Payment</h1>
    <div class="book-details">
        <h1>Total Price: $<?= number_format($totalPrice, 2) ?></h1>
        <button id="openModal">Proceed to Payment</button>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Select Payment Method</h2>
        <form id="paymentForm" action="process_payment.php" method="post">
            <input type="hidden" name="total_price" value="<?= htmlspecialchars($totalPrice) ?>">
            <label>Address:</label>
            <input type="text" name="address" required>
            <label>PIN:</label>
            <input type="number" name="pin-code" required>
            <label>
                <input type="radio" name="payment_method" value="credit_card" required> Credit Card
            </label>
            <label>
                <input type="radio" name="payment_method" value="paypal"> PayPal
            </label>
            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</div>

<script>
    var modal = document.getElementById("paymentModal");
    var btn = document.getElementById("openModal");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>
