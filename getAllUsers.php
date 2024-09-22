<?php

function getAllUsers($con) {
    $sql = " SELECT `username`, `email`, `role` FROM `signup`";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();

    // Fetch all rows
    $users = $result->fetch_all(MYSQLI_ASSOC); 

    return $users;
}
?>