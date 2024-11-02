<?php
include "config.php";

session_start();

    // for image uploading
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //target directory for uploading image
    $targetDir = "img/";
    $fileName = basename($_FILES["cover"]["name"]); //var filename used to upload name to column cover in db
    $targetFilePath = $targetDir . $fileName;

    // check if the file is an image
    $check = getimagesize($_FILES["cover"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Move the uploaded file to the target directory
    if (!move_uploaded_file($_FILES["cover"]["tmp_name"], $targetFilePath)) {
        die("Sorry, there was an error uploading your file.");
    }

    // retrieve form values
    $title = $_POST['title'];
    $authorName = $_POST['author'];
    $categoryName = $_POST['category'];

    // check if author exists, if not insert it
    $stmt = $conn->prepare("SELECT author_id FROM author WHERE name = ?");
    $stmt->bind_param("s", $authorName);
    $stmt->execute();
    $stmt->bind_result($authorId);
    $stmt->fetch();
    $stmt->close();

    // if author does not exist, insert new author to table author
    if (!$authorId) {
        $stmt = $conn->prepare("INSERT INTO author (name) VALUES (?)");
        $stmt->bind_param("s", $authorName);
        $stmt->execute();
        $authorId = $conn->insert_id; // get the newly inserted author_id
        $stmt->close();
    }

    // check if category exists, if not insert it
    $stmt = $conn->prepare("SELECT category_id FROM category WHERE name = ?");
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $stmt->bind_result($categoryId);
    $stmt->fetch();
    $stmt->close();

    // if category does not exist, insert new category to table category
    if (!$categoryId) {
        $stmt = $conn->prepare("INSERT INTO category (name) VALUES (?)");
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $categoryId = $conn->insert_id; // get the newly inserted category_id
        $stmt->close();
    }

    // Now inserting the book
    $stmt = $conn->prepare("INSERT INTO books (title, author_id, price, description, category_id, cover) VALUES (?, ?, ?, ?, ?,?)");
    $stmt->bind_param("siisis", $title, $authorId, $_POST['price'], $_POST['description'], $categoryId, $fileName);

    if ($stmt->execute()) {
        // stores a success message in the session if success
        $_SESSION['success_message'] = "New book added successfully!";
    } else {
        // or an error message 
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }

    // close statement
    $stmt->close();

    // redirect to the same page to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <style>
                body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
        }
        .navbar {
            background-color: #333;
            color: #fff;
            padding: 30px;
            text-align: left;
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
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
        }
        input, textarea {
            margin-bottom: 10px;
            padding: 5px;
        }
        input[type="submit"] {
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
        }
        h2 {
            text-align: center;
            font-size: 50px;
        }
        .message {
            text-align: center;
            color: green;
        }
        .error {
            text-align: center;
            color: red;
        }
    </style>
</head>
<body>
<div class="navbar">
        <a href="admin.php">Back</a>
    </div>
    
    <div class="container">
    <h2>Add Book</h2>

    <?php
    //checks whether any message occured
    //then display it if occured
    if (isset($_SESSION['success_message'])) {
        echo "<p class='message'>" . $_SESSION['success_message'] . "</p>";
        unset($_SESSION['success_message']); // removes message from session to prevent them from displaying again
    }
    if (isset($_SESSION['error_message'])) {
        echo "<p class='error'>" . $_SESSION['error_message'] . "</p>";
        unset($_SESSION['error_message']); // same here
    }
    ?>

    <form action="#" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="author">Author Name:</label>
        <input type="text" id="author" name="author" required>

        <label for="price">price:</label>
        <textarea id="price" name="price" rows="4" required></textarea>


        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required>

        <label for="cover">Cover Image:</label>
        <input type="file" id="cover" name="cover" accept="image/*" required>

        <input type="submit" value="Add Book">
    </form>
    </div>
</body>
</html>
