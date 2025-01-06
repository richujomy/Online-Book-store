<?php 
include "config.php";
session_start();

$book_id = $_GET['book_id'];
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

$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$book['title']?> - Details</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="home2.css">
    
    <style>
    .book-details {
        background-color: #fff;
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        /* box-shadow: 0 0 10px rgba(0,0,0,0.1); */
        border-radius: 8px;
    }
    
    .book-image {
        max-width: 300px;
        margin: 0 auto 2rem;
        display: block;
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 1rem 0;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .btn-cart, .btn-buy {
        min-width: 120px;
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg custom-navbar">
        <h1>BookFlix</h1>
        <div class="collapse navbar-collapse">
            <div class="ml-auto d-flex align-items-center">
                <i class="fas fa-user-circle fa-2x"></i>
                <p class="mail-p"><?php echo $email ?></p>
                <a href="myCart.php"><i class="fa fa-shopping-cart" style="font-size:24px"></i></a>
                <a class="nav-link" href="myorder.php">MyOrders</a>
                <a href="logout.php" class="btn btn-danger btn-sm ml-2">Logout</a>
            </div>
        </div>
    </nav>

    <div class="book-details">
        <img src="./img/<?=$book['cover']?>" alt="<?=$book['title']?>" class="book-image">
        <h2 class="text-center mb-4"><?=$book['title']?></h2>
        
        <div class="row">
            <div class="col-md-6">
                <p><strong>Author:</strong> <?=$book['author_name'] ?? 'No author found'?></p>
                <p><strong>Category:</strong> <?=$book['category_name'] ?? 'No category found'?></p>
                <p><strong>Price:</strong> ₹<?=$book['price']?></p>
                <p><strong>Available Stock:</strong> <?=$book['quantity']?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Description:</strong></p>
                <p><?=$book['description']?></p>
            </div>
        </div>

        <div class="quantity-controls">
            <button class="btn btn-sm btn-outline-secondary" onclick="decrementQuantity(<?=$book['quantity']?>)">-</button>
            <input type="number" id="quantity" value="1" min="1" max="<?=$book['quantity']?>" 
                   class="form-control form-control-sm mx-2" style="width: 60px;" readonly>
            <button class="btn btn-sm btn-outline-secondary" onclick="incrementQuantity(<?=$book['quantity']?>)">+</button>
        </div>

        <div class="action-buttons">
            <a href="#" class="btn btn-success btn-cart" onclick="return updateCartLink(<?=$book['book_id']?>)">Add to Cart</a>
            <a href="home.php" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <script>
    function incrementQuantity(maxQuantity) {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < maxQuantity) {
            input.value = currentValue + 1;
        } else {
            alert('You cannot exceed the available stock of ' + maxQuantity + ' books.');
        }
    }

    function decrementQuantity() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    function updateCartLink(bookId) {
        const quantity = document.getElementById('quantity').value;
        window.location.href = `addToCart.php?book_id=${bookId}&quantity=${quantity}`;
        return false;
    }
    </script>
</body>
</html>