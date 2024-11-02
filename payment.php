<?php
session_start();
include "config.php";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch book details
$book_id = intval($_GET['book_id']); // Typecast to int for security
$query = "SELECT books.*, author.name AS author_name, category.name AS category_name 
          FROM books 
          LEFT JOIN author ON books.author_id = author.author_id 
          LEFT JOIN category ON books.category_id = category.category_id 
          WHERE books.book_id = $book_id";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    echo "Book not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file here -->
    <style>
        /* Basic styling for the modal */
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
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
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
    <div class="payment-container">
        <h1>Proceed to Payment</h1>
        <div class="book-details">
            <h1><?= htmlspecialchars($book['title']) ?></h1>
            <img src="./img/<?= htmlspecialchars($book['cover']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="img" width="200">
            <p>Price: $<?= htmlspecialchars($book['price']) ?></p>
            <button id="openModal">Proceed to Payment</button>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Select Payment Method</h2>
            <form id="paymentForm" action="process_payment.php" method="post">
                <input type="hidden" name="book_id" value="<?= htmlspecialchars($book_id) ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($book['price']) ?>">
                <label>Address:</label>
                <input type="textarea" name="address" required>
                <label>PIN</label>
                <input type="numeric" name="pin-code" required>
                <br>
                <label>
                    <input type="radio" name="payment_method" value="credit_card" required> Credit Card
                </label><br>
                <label>
                    <input type="radio" name="payment_method" value="paypal"> PayPal
                </label><br>
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
