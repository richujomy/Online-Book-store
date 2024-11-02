<?php
session_start();
include "config.php";

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// get user email from session of login page
$email = $_SESSION['email'];

// Fetch userid from the database
$query = "SELECT user_id FROM signup WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['user_id']; 
} else {
    echo "User not found.";
    exit();
}

$bookId = $_GET['book_id'];
$quantity = 1; // Default quantity

// Fetch book title for insertion
$query = "SELECT title, cover, price FROM books WHERE book_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
    $title = $book['title'];
    $cover = $book['cover'];
    $price = $book['price'];

    // Insert into cart
    $insertQuery = "INSERT INTO cart_items (user_id, book_id, title, cover, price, quantity) VALUES (?, ?, ?, ?,?,?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("iissdi", $userId, $bookId, $title, $cover, $price, $quantity);



    if ($insertStmt->execute()) {
        $_SESSION['message'] = "Book added to cart successfully!";
        header("Location: home.php");
        exit();
    } else {
        echo "Error adding to cart: " . $conn->error;
    }
} else {
    echo "Book not found.";
}
?>