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
            font-family: Arial, sans-serif;
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
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
		h1{
			display: flex;
			justify-content: center;
			align-items: center;
			height: 70vh;
		}
        table, th, td{
            border: 1px solid;
        }
        table{
            width: 70%;
            margin: 0 auto 0 auto;
            margin: 50px auto;
        }
        .message{
            text-align: center;
            color: red;
        }
        </style>
</head>
<body>
    <div class="navbar">
        <a href="admin.php">Admin</a>
        <a href="adminManageusers.php">Manage Users</a>
        <a href="addBook.php">Add Book</a>
        <a href="#logout">Logout</a>
    </div>

    <?php if ($message){
            echo"<p class='message'>".$message."</p>";
        }        
       ?>
        <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Descritpion</th>
            <th>Category</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?=$book['id']?></td> 
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
                    <a href="adminDelete.php?id=<?php echo  $book['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        
        <?php if (empty($books)): ?>
            <tr>
                <td colspan="5">No books found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
     <table>
    <th>Authors</th>
     <tr>
        <td>
            <?php if (empty($authors)): ?>
            No author found
            <?php else: ?>
            <?php foreach ($authors as $author): ?>
    <tr>
        <td><?= htmlspecialchars($author['name']) ?></td>
    </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </td>


                    </tr>
                </table>
    <!-- <div class="footer">
        &copy; BookFlix. All rights reserved.
    </div> -->
</body>
</html>
