<?php
include "config.php";

session_start();
// if id is set in the URL
if (!empty($_GET['id'])) {
    $id = $_GET['id']; //store id to var id

    //statement to retrieve the author id associated with the book
    $stmt = $conn->prepare("SELECT author_id FROM `books` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
        $authorId = $book['author_id'];

        //  statement to delete the book
        $stmt = $conn->prepare("DELETE FROM `books` WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // statement to delete the author
        $stmt = $conn->prepare("DELETE FROM `author` WHERE author_id = ?");
        $stmt->bind_param("i", $authorId);
        $stmt->execute();

        $_SESSION['message'] = 'Deletion Successful';
        header("Location: admin.php");
        exit();
    } else {
        $_SESSION['message'] = "Book not found";
    }

    } else {
    $message = "";
}
?>
