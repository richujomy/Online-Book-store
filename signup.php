<?php
session_start();
$error_message= '';
include "config.php";

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

        // Check if the email already exists
        $emailCheckSql = "SELECT * FROM signup WHERE email = '$email'";
        $emailResult = mysqli_query($conn, $emailCheckSql);
        $emailNum = mysqli_num_rows($emailResult);
    
        // Check if the username already exists
        $usernameCheckSql = "SELECT * FROM signup WHERE username = '$username'";
        $usernameResult = mysqli_query($conn, $usernameCheckSql);
        $usernameNum = mysqli_num_rows($usernameResult);
    
        if($emailNum > 0){
            $_SESSION['login_error'] = "Email already exists";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();

        } elseif($usernameNum > 0) {
            $_SESSION['login_error'] = "username already exists";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();

        } else {
            // Insert new user if both email and username are unique
            $insert = "INSERT INTO signup(username, email, password) 
                       VALUES ('$username', '$email', '$password')";
            mysqli_query($conn, $insert);
            header("Location: login.php");
            exit();
        }
    
}
        if (isset($_SESSION['login_error'])) {

            $error_message = $_SESSION['login_error'];
        unset($_SESSION['login_error']);

        }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-message{
        color: red;
        margin-top: 10px;
        text-align: center;
        }
        </style>
</head>
<body>
<div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2>Signup</h2>
            </div>
            <form id="signup-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="text" id="username" name="username" placeholder="Username" required>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit" name="submit">Sign Up</button>
                <div class="error-message">
                    <!-- login error message is displaying inside the form -->
                    <?php echo $error_message ? htmlspecialchars($error_message) : ''; ?>
                </div>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>