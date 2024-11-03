<?php

function getCart($con) {
    $email = $_SESSION['email'];
    $sql = "SELECT cart_items.*, books.title, books.cover FROM cart_items
            JOIN books ON cart_items.book_id = books.book_id
            WHERE cart_items.user_id = (SELECT user_id FROM signup WHERE email = ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>