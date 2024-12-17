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
        h2 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Form Styling */
        #paymentForm {
            max-width: 500px;
            margin: 0 auto;
            padding: 10px 25px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s ease-in-out;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
        }
        button:hover {
            background-color: #0056b3;
        }

        /* Radio Button Styles */
        .payment-methods {
            margin-top: 20px;
            font-size: 14px;
        }

        .payment-methods label {
            display: block;
            margin-bottom: 10px;
            font-weight: normal;
        }

        .payment-methods input {
            margin-right: 8px;
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
    <a href="home.php">Back to Home</a>
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
    
    <div class="form-group">
        <label for="mobile">Mobile number:</label>
        <input type="text" name="address" id="mobile" pattern="[1-9]{1}[0-9]{9}" title="10 digits" required>
    </div>

    <div class="form-group">
        <label for="address">Address:</label>
        <input type="text" name="address" id="address" required>
    </div>

    <div class="form-group">
        <label for="pin-code">PIN:</label>
        <input type="number" name="pin-code" id="pin-code" required>
    </div>

    <fieldset class="payment-methods">
        <legend>Select Payment Method</legend>
        <label>
            <input type="radio" name="payment_method" value="credit_card" required> Credit Card
        </label>
        <label>
            <input type="radio" name="payment_method" value="paypal"> PayPal
        </label>
    </fieldset>

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
