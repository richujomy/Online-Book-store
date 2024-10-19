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

        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
        }
        .card-img-top {
            height: 70%;
            object-fit: fill;
        }
        .card-body {
            padding: 4px; 
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
        
        </style>
</head>
<body>
<nav class="navbar navbar-expand-lg custom-navbar">
        <h1>BookFlix</h1>
        <div class="collapse navbar-collapse">
            <div class="ml-auto d-flex align-items-center">
                <i class="fas fa-user-circle fa-2x"></i>
                <h2><?php echo $email ?></h2>
                <a href="logout.php" class="btn btn-danger btn-sm ml-2">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container d-flex justify-content-center mt-3">
    <form class="form-inline">
        <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
</div>

    <section class=" p-5">
        <div class="container">
            <div class="row">
                <?php foreach ($books as $index => $book): ?>
                    <div class="col-md-2 pt-3">  
                        <div class="card" style="height: 17em;"> 
                            <img src="./img/<?=$book['cover']?>" class="card-img-top" alt="<?=$book['title']?>">
                            <div class="card-body">
                                <h5 class="card-title"><?=$book['title']?></h5>
                                <!-- <p class="card-author">
                                    <?php
                                        foreach ($authors as $author) {
                                            if ($author['author_id'] == $book['author_id']) {
                                                echo $author['name'];
                                            }
                                        }
                                    ?>
                                </p> -->
                                <a href="bookDetails.php?book_id=<?=$book['book_id']?>" class="btn btn-info btn-sm">Details</a>
                                <a href="buy.php?book_id=<?=$book['book_id']?>" id="btn-buy" class="btn btn-success btn-sm">Buy</a>
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
