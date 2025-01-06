<?php
session_start();
include "config.php";

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get user email from session
$email = $_SESSION['email'];

// Fetch user ID from the database
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

if (isset($_GET['book_id']) && isset($_GET['quantity'])) {
    $bookId = intval($_GET['book_id']);
    $quantity = intval($_GET['quantity']); // Get quantity from the request

    // Fetch book details
    $query = "SELECT title, cover, price, quantity AS stock FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        $title = $book['title'];
        $cover = $book['cover'];
        $price = $book['price'];
        $stock = $book['stock'];

        // Check if sufficient stock is available
        if ($quantity > $stock) {
            $_SESSION['message'] = "Not enough stock available.";
            header("Location: home.php");
            exit();
        }

        // Insert into cart
        $insertQuery = "INSERT INTO cart_items (user_id, book_id, title, cover, price, quantity) VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iissdi", $userId, $bookId, $title, $cover, $price, $quantity);

        if ($insertStmt->execute()) {
            // Update stock in the books table
            $updateQuery = "UPDATE books SET quantity = quantity - ? WHERE book_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ii", $quantity, $bookId);
            $updateStmt->execute();

            $_SESSION['message'] = "Book added to cart successfully!";
            header("Location: home.php");
            exit();
        } else {
            echo "Error adding to cart: " . $conn->error;
        }
    } else {
        echo "Book not found.";
    }
} else {
    echo "Invalid request.";
}
?>
