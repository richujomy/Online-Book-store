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
$books = getFilteredBooks($conn, $searchTerm, $categoryId);

$categoryName = '';
if ($categoryId !== null && !empty($categories)) {
    foreach ($categories as $category) {
        if ($category['category_id'] == $categoryId) {
            $categoryName = $category['name'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="home2.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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
            <a href="home.php" class="btn btn-primary btn-sm ml-2">Back to Home</a>
            <a href="logout.php" class="btn btn-danger btn-sm ml-2">Logout</a>
        </div>
    </div>
</nav>

<section class="p-5">
    <div class="container">
        <?php if (!empty($searchTerm) || !empty($categoryId)): ?>
            <h2 class="mb-4">
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
                <div class="col-12 text-center">
                    <p class="alert alert-info">No results found.</p>
                </div>
            <?php else: ?>
                <?php foreach ($books as $index => $book): ?>
                    <div class="col-md-2 pt-3 mb-4">
                        <div class="card">
                            <a href="bookDetails.php?book_id=<?=$book['book_id']?>" class="book-image-link">
                                <img src="./img/<?=$book['cover']?>" class="card-img-top" alt="<?=$book['title']?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?=$book['title']?></h5>
                                <p class="price">₹<?=$book['price']?></p>

                                <div class="d-flex justify-content-center align-items-center">
                                    <button class="btn btn-sm btn-outline-secondary px-1 d-flex align-items-center justify-content-center"
                                            style="height: 20px; min-height: 20px; line-height: 0;" 
                                            onclick="decrementQuantity(this, <?=$book['quantity']?>)">-</button>
                                    <input type="number" value="1" min="1" 
                                           max="<?=$book['quantity']?>" 
                                           class="form-control form-control-sm mx-1" 
                                           style="width: 50px; height: 20px; padding: 0; text-align: center; font-size: 12px;" readonly>
                                    <button class="btn btn-sm btn-outline-secondary px-1 d-flex align-items-center justify-content-center" 
                                            style="height: 20px; min-height: 20px; line-height: 0;" 
                                            onclick="incrementQuantity(this, <?=$book['quantity']?>)">+</button>
                                </div>

                                <a href="#" 
                                   class="btn-cart search-input" 
                                   onclick="return updateCartLink(this, <?=$book['book_id']?>)">Add to cart</a>
                            </div>
                        </div>
                    </div>
                    <?php if (($index + 1) % 6 == 0): ?>
                        </div><div class="row">
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
function incrementQuantity(button, maxQuantity) {
    const input = button.parentElement.querySelector('input[type="number"]');
    const currentValue = parseInt(input.value);
    if (currentValue < maxQuantity) {
        input.value = currentValue + 1;
    } else {
        alert('You cannot exceed the available stock of ' + maxQuantity + ' books.');
    }
}

function decrementQuantity(button) {
    const input = button.parentElement.querySelector('input[type="number"]');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function updateCartLink(button, bookId) {
    const input = button.parentElement.querySelector('input[type="number"]');
    const quantity = input.value;
    button.href = `addToCart.php?book_id=${bookId}&quantity=${quantity}`;
    return true;
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>