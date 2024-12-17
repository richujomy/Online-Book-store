<?php
session_start();
include "config.php";
include "getAllBooks.php";

$email = $_SESSION['email'];

include "getAuthor.php";
$authors = getAuthor($conn);
include "getCategory.php";
$categories = getCategory($conn);

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Ensure categoryId is used directly for filtering
$books = getFilteredBooks($conn, $searchTerm, $categoryId);

// Get category name for display purposes
$categoryName = '';
if ($categoryId !== null && isset($categories[$categoryId])) {
    $categoryName = $categories[$categoryId]['name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="home.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar">
<a href="home.php">Back</a>
</nav>

<section class="p-5">
    <div class="container">
    <?php if (!empty($searchTerm) || !empty($categoryId)): ?>
    <h2>
        Search Results
        <?php if (!empty($searchTerm)): ?>
            for "<?php echo htmlspecialchars($searchTerm); ?>"
        <?php endif; ?>
        <?php if (!empty($categoryId)): ?>
            in "<?php echo htmlspecialchars($categoryName); ?>" category
        <?php endif; ?>
    </h2>
    <?php endif; ?>
        <div class="row">
            <?php if (empty($books)): ?>
                <p>No results found.</p>
            <?php else: ?>
                <?php foreach ($books as $index => $book): ?>
        <div class="col-md-2 pt-3 mb-4">
            <div class="card">
                <a href="bookDetails.php?book_id=<?=$book['book_id']?>" class="book-image-link">
                    <img src="./img/<?=$book['cover']?>" class="card-img-top" alt="<?=$book['title']?>">
                </a>
                <div class="card-body">
                    <h5 class="card-title"><?=$book['title']?></h5>
                    <p class="price">$<?=$book['price']?></p>
                    <a href="addToCart.php?book_id=<?=$book['book_id']?>" class="btn-cart search-input">Add to cart</a>
                </div>
            </div>
        </div>
        <?php if (($index + 1) % 6 == 0): ?>
            </div><div class="row">
        <?php endif; ?>
    <?php endforeach; ?>
            <?php endif; ?>
        </div> <!-- Close row div -->
    </div> <!-- Close container div -->
</section>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>