<?php
include "config.php";
include "getAuthor.php";
include "getCategory.php";

$authors = getAuthor($conn);
$categories = getCategory($conn);

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    //taking the book for edit
    $sql = "SELECT * FROM `books` WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id); //var is the actual value that replace the ? in sql stmnt
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc(); //fetching as associative array: allowing you to access values using those column names
                                     // instead of numeric indices.

    if (!$book) {
        echo "Book not found.";
        exit;
    }
} else {
    echo "Invalid book ID.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the book details
    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $cover = $_POST['cover']; // assuming cover is a text field for simplicity

    $sql = "UPDATE `books` SET `title` = ?, `author_id` = ?, `description` = ?, `category_id` = ?, `cover` = ? WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssi", $title, $author_id, $description, $category_id, $cover, $book_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Book updated successfully.";
        header("Location: admin.php");
        exit;
    } else {
        echo "Error updating book.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <Style>
                body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
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
    </Style>
</head>
<body>
    <h1>Edit Book</h1>
    <form action="adminEdit.php?id=<?= $book_id ?>" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br>

        <label for="author_id">Author:</label>
        <select id="author_id" name="author_id" required>
            <?php foreach ($authors as $author): ?>
                <option value="<?= $author['author_id'] ?>" <?= $author['author_id'] == $book['author_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($author['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($book['description']) ?></textarea><br>

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['category_id'] ?>" <?= $category['category_id'] == $book['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="cover">Cover:</label>
        <input type="text" id="cover" name="cover" value="<?= htmlspecialchars($book['cover']) ?>" required><br>

        <button type="submit">Update Book</button>
    </form>
</body>
</html>