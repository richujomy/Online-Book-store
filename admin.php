<?php
include "config.php";

include "getAllBooks.php";
$books =  getAllBooks($conn);

include "getAuthor.php";
$authors =  getAuthor($conn);
// print_r($authors);

include "getCategory.php";
$categories =  getCategory($conn);
// print_r($categories);



include "adminDelete.php";

// taking the deletion message and storing in a variable
$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying
}

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #333;
            color: #fff;
            padding: 30px;
            text-align: center;
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
		h1{
			display: flex;
			justify-content: center;
			align-items: center;
			height: 70vh;
		}
        h2 {
            text-align: center;
            font-size: 40px;
        }
        table, th, td{
            /* border: 1px solid; */
            border-bottom: 1px solid #DDD;
        }   
        th, td{
            padding: 20px;
            }
        th {
            background-color: #f2f2f2; /* Header background color */
        }
        table{
            width: 70%;
            margin: 0 auto 0 auto;
            margin: 50px auto;
            text-align: center;
            border-collapse: collapse;
            background-color:	#EEEDEB;
        }
        .books th{
            padding-top: 12px;
            padding-bottom: 12px;
        }
        .message{
            text-align: center;
            color: red;
        }
        .authors{
            width: 30%;
        }
        .authors th{
            padding-top: 15px;
            padding-bottom: 15px;
            font-size: 20px;
        }
        .delete-button {
            background-color: red;
            color: white; 
            border: none;
            font-size: 16px; 
            border-radius: 5px; 
        }
        button:hover {
            background-color: darkred; 
        }
        button a{
            text-decoration: none;
            color: inherit; 
        }
        .edit-button{
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
        }
        </style>
</head>
<body>
    <div class="navbar">
        <a href="manageOrders.php">Manage orders</a>
        <a href="adminManageusers.php">Manage Users</a>
        <a href="addBook.php">Add Book</a>
        <a href="logout.php">Logout</a>
    </div>
        <h2>Book Details</h2>
    <?php if ($message){
            echo"<p class='message'>".$message."</p>";
        }        
       ?>
        <table class="books">
    <thead>
        <tr>
            <!-- <th>ID</th> -->
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
            <th>Descritpion</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <!-- <td><?=$book['id']?></td>  -->
                <td>
                <!-- image imported by giving the same name for both image and table cover -->
                    <img width="100" src="./img/<?=$book['cover']?>"> 
                </td>     
                <td>
                    <?php if($authors==0){
                        echo "No author found";
                    }else{
                        foreach($authors as $author){ // iterate through the list of authors retrieved from the database
                            if($author['author_id'] == $book['author_id']){   
                           // display the name of the author matching the book's author_id
                                echo $author['name'];  
                            }
                        }
                    }

                    ?>
                    <td>₹<?=$book['price']?></td>
                </td>
                <td><?=$book['description']?></td>
                <td> 
                <!-- same logic applied as in the previous td-->
                <?php if($categories==0){
                        echo "No category found";        
                    }else{
                        foreach($categories as $category){ 
                            if($category['category_id'] == $book['category_id']){ 
                                echo $category['name'];  
                            }
                        }
                    }
                    ?>
                </td>
                <td>
                    <button class="delete-button"><a href="adminDelete.php?id=<?php echo  $book['book_id']; ?>">Delete</a>
                    </button>
                    <br>
                    <br>
                    <button class="edit-button"><a href="adminEdit.php?id=<?php echo  $book['book_id']; ?>">Edit</a>
                    </button>
                    
                </td>
            </tr>
        <?php endforeach; ?>
        
        <?php if (empty($books)): ?>
            <tr>
                <td colspan="5">No books found.</td>
            </tr>
        <?php endif; ?>
    </tbody>



    <table class="authors">
    <thead>
        <tr>
            <th>Authors</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($authors)): ?>
            <tr>
                <td>No author found</td>
            </tr>
        <?php else: ?>
            <?php foreach ($authors as $author): ?>
                <tr>
                    <td><?= htmlspecialchars($author['name']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
