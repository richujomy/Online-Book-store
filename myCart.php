<?php
include "config.php";

function getCart($con) {
    $sql = "SELECT `title`, `cover`, `price`, `quantity` FROM `cart_items`";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();

    // Fetch all rows
    $cart = $result->fetch_all(MYSQLI_ASSOC); 

    return $cart;
}

$carts = getCart($conn);
$totalPrice = 0; // for display cart total
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #323643;
            margin: 0;
            padding: 0;
        }
        h1{
            text-align: center;
            color: #FFFFFF;
        }

        h3 {
            color: #333;
            margin-top: 20px;
            text-align: center;
            color: #FFFFFF;
        }

        table {
            margin: 0 auto 0 auto;
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

    </style>
</head>
<body>
<div class="navbar">
        <a href="home.php">Back</a>
    </div>
    
    <h1>Your Shopping Cart</h1>

    <table >
        <th></th>
        <th>Book name</th>
        <th>Price</th>
        <th>Quantity</th>
    <?php foreach($carts as $cart): ?>
        <tr>
            <td><img width="100" src="./img/<?=$cart['cover']?>"></td>
            
            <td><?=$cart['title']?></td>
            
            <td>$<?=$cart['price']?></td>
           
            <td><?=$cart['quantity']?></td>
        </tr>
        <?php 
            // calculate total price for the current item
            $totalPrice += $cart['price'] * $cart['quantity'];
        ?>
    <?php endforeach; ?>
    </table>

    <h3>Total Price: $<?=number_format($totalPrice, 2)?></h3> <!-- display total price -->

</body>
</html>
