<?php
//get all books

function getAllBooks($con) {
    $sql = "SELECT `book_id`, `title`, `author_id`, `description`, `category_id`, `cover` FROM `books`";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();

    // Fetch all rows
    $books = $result->fetch_all(MYSQLI_ASSOC); 

    return $books;
}




        # Get  book by ID function
// function get_book($con, $id){
//     $sql  = "SELECT * FROM books WHERE id=?";
//     $stmt = $con->prepare($sql);
//     $stmt->execute([$id]);
 
//     if ($stmt->rowCount() > 0) {
//           $book = $stmt->fetch();
//     }else {
//        $book = 0;
//     }
 
//     return $book;
//  }
 
 
//  # Search books function
//  function search_books($con, $key){
//     # creating simple search algorithm :) 
//     $key = "%{$key}%";
 
//     $sql  = "SELECT * FROM books 
//              WHERE title LIKE ?
//              OR description LIKE ?";
//     $stmt = $con->prepare($sql);
//     $stmt->execute([$key, $key]);
 
//     if ($stmt->rowCount() > 0) {
//          $books = $stmt->fetchAll();
//     }else {
//        $books = 0;
//     }
 
//     return $books;
//  }
 
//  # get books by category
//  function get_books_by_category($con, $id){
//     $sql  = "SELECT * FROM books WHERE category_id=?";
//     $stmt = $con->prepare($sql);
//     $stmt->execute([$id]);
 
//     if ($stmt->rowCount() > 0) {
//          $books = $stmt->fetchAll();
//     }else {
//        $books = 0;
//     }
 
//     return $books;
//  }
 
 
//  # get books by author
//  function get_books_by_author($con, $id){
//     $sql  = "SELECT * FROM books WHERE author_id=?";
//     $stmt = $con->prepare($sql);
//     $stmt->execute([$id]);
 
//     if ($stmt->rowCount() > 0) {
//          $books = $stmt->fetchAll();
//     }else {
//        $books = 0;
//     }
 
//     return $books;
//  }



 ?>