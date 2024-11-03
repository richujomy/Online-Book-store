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
    $books = getAllBooks($conn, $searchTerm); // Pass the search term to the function
    
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
        .btn-details{
            background-color: #323643;
            border-radius: 50px 50px 50px 50px;
            width: 90%;
        }
        .filter-container {
            display: flex;
            justify-content: center;
            gap: 80px;
            margin-top: 20px;
            align-items: center;
            font-size: small;
        }
        .filter-dropdown {
            color: #FFFFFF;
            padding: 3px;
            border-radius: 5px;
            border: 1px solid #ccc;
            border-radius: 50px 50px 50px 50px;
            background-color: #323643;
            text-align: center;
            width: 200px;
            height: 30px;
        }
        .clear-filters {
            padding: 8px 16px;
            border-radius: 5px;
            border: 1px solid #dc3545;
            background-color: #dc3545;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .clear-filters:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        /* Only show clear filters button when filters are active */
        .clear-filters.hidden {
            display: none;
        }
        .search-input, .search-btn{
            border-radius: 50px 50px 50px 50px;
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
        
        /* a{
        
            color: inherit;
            text-decoration: none;
        }
        a:hover {
            color: white;
            text-decoration: solid;
        } */
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

        <?php if (isset($_SESSION['message'])) {
         $message = $_SESSION['message'];

         //displaying session message using js alert
        echo "<script type='text/javascript'>alert('$message');</script>";
        unset($_SESSION['message']);
         }
         ?>
         <!-- search bar -->
         <form class="form-inline d-flex justify-content-center mt-3" method="GET" action="">
    <input class="form-control mr-2 search-input" type="search" name="search" placeholder="Search" value="<?php echo htmlspecialchars($searchTerm); ?>" aria-label="Search">
    <button class="btn btn-outline-success search-btn" type="submit">Search</button>
</form>


            
         <!-- catgory and author filter-->
         <div class="container-filter pt-3">
        <div class="filter-container">
            <select class="filter-dropdown" onchange="if(this.value) window.location.href=this.value;">
                <option value="">Browse by Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="?category_id=<?=$category['category_id']?>"
                        <?php echo isset($_GET['category_id']) && $_GET['category_id'] == $category['category_id'] ? 'selected' : ''; ?>>
                        <?=$category['name']?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select class="filter-dropdown" onchange="if(this.value) window.location.href=this.value;">
                <option value="">Browse by Author</option>
                <?php foreach ($authors as $author): ?>
                    <option value="?author_id=<?=$author['author_id']?>"
                        <?php echo isset($_GET['author_id']) && $_GET['author_id'] == $author['author_id'] ? 'selected' : ''; ?>>
                        <?=$author['name']?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Clear filters button - only shown when filters are active -->
            <button 
                class="clear-filters <?php echo (!isset($_GET['category_id']) && !isset($_GET['author_id'])) ? 'hidden' : ''; ?>"
                onclick="window.location.href='<?php echo strtok($_SERVER['REQUEST_URI'], '?'); ?>'"
            >
                Clear Filters
            </button>
        </div>
        </div>


        <!-- <p>New Releases &#8667;</p> -->
    <section class=" p-5">
        <div class="container">
            <div class="row">
                <?php foreach ($books as $index => $book): ?>
                    <div class="col-md-2 pt-3">  
                        <div class="card" style="height: 15em;"> 
                            <img src="./img/<?=$book['cover']?>" class="card-img-top" alt="<?=$book['title']?>">
                            <div class="card-body">
                                <h5 class="card-title"><?=$book['title']?></h5>
                                <p class="price">$<?=$book['price']?></p>
                                <!-- <p class="card-author">
                                    <?php
                                        foreach ($authors as $author) {
                                            if ($author['author_id'] == $book['author_id']) {
                                                echo $author['name'];
                                            }
                                        }
                                    ?>
                                </p> -->
                                <a href="addToCart.php?book_id=<?=$book['book_id']?>" class="btn-cart search-input">Add to cart</a>
                                <br>
                                <a href="bookDetails.php?book_id=<?=$book['book_id']?>" class="btn-details">Details</a>
                            </div>
                        </div>
                    </div>
                    <?php if (($index + 1) % 5 == 0): ?>
                        </div><div class="row">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div> <!-- Close row div -->
        </div> <!-- Close container div -->
    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>