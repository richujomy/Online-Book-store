<?php
include "config.php";

session_start();

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    $sql = "DELETE FROM `signup` WHERE `username` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully.";    //this message will be displayed on manageusers page
    } else {
        $_SESSION['message'] = "Failed to delete user.";
    }

    header("Location: adminManageUsers.php");
    exit();
}

?>