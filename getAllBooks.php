<?php
//get all books

// function getAllBooks($con) {
//     $sql = "SELECT `book_id`, `title`, `author_id`, `price`, `description`, `category_id`, `cover` FROM `books`";
//     $stmt = $con->prepare($sql);
//     $stmt->execute();

//     $result = $stmt->get_result();

//     // Fetch all rows
//     $books = $result->fetch_all(MYSQLI_ASSOC); 

//     return $books;
// }


function getAllBooks($conn, $searchTerm = '') {
    $sql = "SELECT * FROM books";
    if ($searchTerm) {
        $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
        $sql .= " WHERE title LIKE '%$searchTerm%'";
    }
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function getFilteredBooks($conn, $searchTerm = '', $categoryId = '')
{
    $sql = "SELECT * FROM books";
    $params = [];

    if (!empty($searchTerm)) {
        $sql .= " WHERE title LIKE ?";
        $params[] = "%{$searchTerm}%";
    }

    if (!empty($categoryId)) {
        if (strpos($sql, 'WHERE') === false) {
            $sql .= " WHERE category_id = ?";
        } else {
            $sql .= " AND category_id = ?";
        }
        $params[] = $categoryId;
    }

    $sql .= " ORDER BY book_id DESC";

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
 ?>