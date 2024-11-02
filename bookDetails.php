<?php 
include "config.php";


$book_id = $_GET['book_id']; // Typecast to int for security
// Fetch book details directly
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
    <title><?=$book['title']?> - Details</title>

    <style>
        div{
            justify-content: center;

        }
    </style>
</head>
<body>
<div class="container ">
    <h1><?=$book['title']?></h1>
    <img src="./img/<?=$book['cover']?>" alt="<?=$book['title']?>" class="img" width="200">
    <h3>Details</h3>
    <p><strong>Author:</strong> <?=$book['author_name'] ?? 'No author found'?></p>
    <p><strong>Price:</strong> $<?=$book['price'] ?? 'No author found'?></p>
    <p><strong>Category:</strong> <?=$book['category_name'] ?? 'No category found'?></p>
    <p><strong>Description:</strong> <?=$book['description']?></p>
    <a href="payment.php?book_id=<?=$book['book_id']?>" class="btn">Buy</a>
    <a href="home.php" class="btn2">Back to List</a>
</div>
</body>
</html>