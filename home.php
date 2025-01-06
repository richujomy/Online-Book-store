<?php
    session_start();
    // Check if the user is logged in
// if (!isset($_SESSION['email'])) {
//     // If not logged in, redirect to login page
//     header("Location: login.php");
//     exit();
// }
    include "config.php";

    include "getAllBooks.php";
    $books =  getAllBooks($conn);
    
    include "getAuthor.php";
    $authors =  getAuthor($conn);
    
    
    include "getCategory.php";
    $categories =  getCategory($conn);
    $email = $_SESSION['email'];
    // print_r($email);

    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    $categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : '';
    $books = getFilteredBooks($conn, $searchTerm, $categoryId);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <!-- bootstrap 4 cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" 
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="home2.css">
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

        <?php if (isset($_SESSION['message'])) {
         $message = $_SESSION['message'];

         //displaying session message using js alert
        echo "<script type='text/javascript'>alert('$message');</script>";
        unset($_SESSION['message']);
         }
         ?>
         <!-- search bar -->
         <form class=" mt-5 search-bar" method="GET" action="homeSearchResult.php">
    <input class="form-control mr-2 search-input" type="search" name="search" placeholder="Search" value="<?php echo htmlspecialchars($searchTerm); ?>" aria-label="Search">
    <button class="btn-success search-btn" type="submit">Search</button>
</form>

   
         <!-- catgory and author filter-->
         <div class="container-filter mt-5 ">
        <div class="filter-container">
  <select class="filter-dropdown" onchange="window.location.href='homeSearchResult.php?category_id='+this.value;">
    <option value="">Browse by Category</option>
    <?php foreach ($categories as $category): ?>
        <option value="<?=$category['category_id']?>"
            <?php echo isset($_GET['category_id']) && $_GET['category_id'] == $category['category_id'] ? 'selected' : ''; ?>>
            <?=$category['name']?>
        </option>
    <?php endforeach; ?>
</select>

        </div>
        </div>



        <section class="p-5">
    <div class="container">
    <div class="row">
    <?php foreach ($books as $index => $book): ?>
        <div class="col-md-2 pt-3 mb-4">
        <div class="card">
    <a href="bookDetails.php?book_id=<?=$book['book_id']?>" class="book-image-link">
        <img src="./img/<?=$book['cover']?>" class="card-img-top" alt="<?=$book['title']?>">
    </a>
    <div class="card-body">
        <h5 class="card-title"><?=$book['title']?></h5>
        <p class="price">₹<?=$book['price']?></p>

        <!-- Quantity Selection -->
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

        <!-- Add to Cart Button -->
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
</div> <!-- Close row div -->
    </div> <!-- Close container div -->
</section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script>
// Increment Quantity
function incrementQuantity(button, maxQuantity) {
    const input = button.parentElement.querySelector('input[type="number"]');
    const currentValue = parseInt(input.value);
    if (currentValue < maxQuantity) {
        input.value = currentValue + 1;
    } else {
        alert('You cannot exceed the available stock of ' + maxQuantity + ' books.');
    }
}

// Decrement Quantity
function decrementQuantity(button) {
    const input = button.parentElement.querySelector('input[type="number"]');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// Update Add to Cart Link
function updateCartLink(button, bookId) {
    const input = button.parentElement.querySelector('input[type="number"]');
    const quantity = input.value;

    // Update the link dynamically with the selected quantity
    button.href = `addToCart.php?book_id=${bookId}&quantity=${quantity}`;
    return true; // Allow navigation
}
</script>

</body>
</html>