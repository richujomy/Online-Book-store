<?php
function getCategory($con){
    $sql = "SELECT * FROM `category`";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();
    $category = $result->fetch_all(MYSQLI_ASSOC);
    return $category;
}
?>