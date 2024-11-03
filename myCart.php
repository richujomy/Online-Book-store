<?php
include "config.php";
include "getCart.php";


session_start();
$carts = getCart($conn);
$totalPrice = 0; // For display cart total
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
        </tr>
        <?php foreach($carts as $cart): ?>
            <tr>
                <td><img width="100" src="./img/<?= htmlspecialchars($cart['cover']) ?>"></td>
                <td><?= htmlspecialchars($cart['title']) ?></td>
                <td>$<?= number_format($cart['price'], 2) ?></td>
                <td><?= htmlspecialchars($cart['quantity']) ?></td>
            </tr>
            <?php 
                // Calculate total price for the current item
                $totalPrice += $cart['price'] * $cart['quantity'];
            ?>
        <?php endforeach; ?>
    </table>
    <h3>Total Price: $<?= number_format($totalPrice, 2) ?></h3> <!-- Display total price -->
    <a href="payment.php" class="btn-buy">Buy Now</a> <!-- Adjusted button -->
<?php endif; ?>

</body>
</html>
