<?php
function getAuthor($con){
    $sql = "SELECT * FROM `author`";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();
    $author = $result->fetch_all(MYSQLI_ASSOC);
    return $author;
}
?>