<?php
session_start();
// Initializing an empty variable for login error
$error_message = '';
include "config.php";

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check admin credentials from the database
    $sql_admin = "SELECT * FROM signup WHERE email = '$email' AND password = '$password' AND role = 'admin'";
    $result_admin = mysqli_query($conn, $sql_admin);

    if (mysqli_num_rows($result_admin) > 0) {
        // Admin login
        $row_admin = mysqli_fetch_assoc($result_admin);
        $_SESSION['role'] = 'admin'; // Set session role for admin
        $_SESSION['email'] = $row_admin['email'];
        header("Location: admin.php"); // Redirect to admin dashboard
        exit();
    } else {
        // Check user credentials from the database
        $sql_user = "SELECT * FROM signup WHERE email = '$email' AND password = '$password' AND role = 'user'";
        $result_user = mysqli_query($conn, $sql_user);

        if (mysqli_num_rows($result_user) > 0) {
            // User login
            $row_user = mysqli_fetch_assoc($result_user);
            $_SESSION['role'] = 'user'; // Set session role for user
            $_SESSION['email'] = $row_user['email'];
            header("Location: home.php"); // Redirect to user dashboard
            exit();
        } else {
            // Invalid credentials
            $_SESSION['login_error'] = "Email and Password not matching";

            // Redirect to the same page to avoid resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// Check if there's an error message in the session
if (isset($_SESSION['login_error'])) {
    // Placing that error message inside this variable
    $error_message = $_SESSION['login_error'];
    // Clear the error message from the session
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
        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        body {
            background-image: url("./img/bglogin.jpg");
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <div class="form-header">
            <h2>Login</h2>
        </div>
        <form id="login-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="email" id="login-email" name="email" placeholder="Email" required>
            <input type="password" id="login-password" name="password" placeholder="Password" required>
            <button type="submit" name="submit">Login</button>

            <div class="error-message">
                <!-- Login error message is displayed inside the form -->
                <?php echo $error_message ? htmlspecialchars($error_message) : ''; ?>
            </div>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </div>
</div>
</body>
</html>
