<?php
session_start();
include "config.php";
include "getAllBooks.php";

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$books = getAllBooks($conn, $searchTerm);
$email = $_SESSION['email'];

include "getAuthor.php";
$authors = getAuthor($conn);
include "getCategory.php";
$categories = getCategory($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
         body{
            font-family: "Poppins";
            margin: 0;
            padding: 0;
            background-color: #323643;
        }
        

        .custom-navbar {
            background-color:#28282B; 
            color: #FFFFFF;
            height: 100px;
        }
        .custom-navbar h1{
            color: #FFFFFF;
            font-size: 25px;
            font-weight: 600;
        }
        .custom-navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
        }
        .custom-navbar a:hover {
            background-color: #555;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            /* overflow: hidden; */
            text-align: center;
        }
        .card-img-top {
            height: 60%;
            object-fit: fill;
        }
        .card-body {
            padding: 4px; 
        }
        .price{
            margin: 0;
        }
        .card-title {
            margin-bottom: 0.1rem; 
        }
        .card-author {
            margin: 0.1rem 0; 
        }
        .btn {
          
            margin: 0.5rem 0; 
            border-radius: 10px;
        }
        #btn-buy{
            border-radius: 10px;
        }
        .mail-p{
            margin: 10px;
        }
        .btn-cart{
            height: 30px;
            width: 120px;
            border-radius: 50px;
            display: block;
            margin: 5px auto auto auto;
            background-color: green;
            color: white;
            text-align: center;
            line-height: 30px;
            text-decoration: none;
        }  
        .btn-cart a{
            text-decoration: none;
        }
        .btn-cart:hover {
            background-color: green;
        }
        .btn-details{
            background-color: #323643;
            border-radius: 50px 50px 50px 50px;
            width: 90%;
        }
        .custom-navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
        }
        .custom-navbar a:hover {
            background-color: #555;
        }
        
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar">
<a href="home.php">Back</a>
</nav>

<section class="p-5">
    <div class="container">
        <h2>Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
        <div class="row">
            <?php if (empty($books)): ?>
                <p>No results found.</p>
            <?php else: ?>
                <?php foreach ($books as $index => $book): ?>
                    <div class="col-md-2 pt-3">  
                        <div class="card" style="height: 15em;"> 
                            <img src="./img/<?php echo $book['cover']; ?>" class="card-img-top" alt="<?php echo $book['title']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $book['title']; ?></h5>
                                <p class="price">$<?php echo $book['price']; ?></p>
                                <a href="addToCart.php?book_id=<?php echo $book['book_id']; ?>" class="btn-cart">Add to cart</a>
                                <br>
                                <a href="bookDetails.php?book_id=<?php echo $book['book_id']; ?>" class="btn-details">Details</a>
                            </div>
                        </div>
                    </div>
                    <?php if (($index + 1) % 5 == 0): ?>
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