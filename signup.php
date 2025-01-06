<?php
session_start();
$error_message = '';
include "config.php";

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobilenumber = $_POST['mobilenumber'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];

    // Check if the email already exists
    $emailCheckSql = "SELECT * FROM signup WHERE email = '$email'";
    $emailResult = mysqli_query($conn, $emailCheckSql);
    $emailNum = mysqli_num_rows($emailResult);

    // Check if the username already exists
    $usernameCheckSql = "SELECT * FROM signup WHERE username = '$username'";
    $usernameResult = mysqli_query($conn, $usernameCheckSql);
    $usernameNum = mysqli_num_rows($usernameResult);

    if ($emailNum > 0) {
        $_SESSION['login_error'] = "Email already exists";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif ($usernameNum > 0) {
        $_SESSION['login_error'] = "Username already exists";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Insert new user if all validations pass
        $insert = "INSERT INTO signup(username, email, password, mobilenumber, address, pincode) 
                   VALUES ('$username', '$email', '$password', '$mobilenumber', '$address', '$pincode')";
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
    <title>Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('./img/bglogin.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-header h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #444;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form input, form textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }
        form textarea {
            resize: none;
        }
        form input:focus, form textarea:focus {
            border-color: #007bff;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        p {
            text-align: center;
            margin-top: 10px;
        }
        p a {
            color: #007bff;
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
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

                <input type="text"  id="username" name="username" placeholder="Username"pattern="^(?!^\d+$)[a-zA-Z0-9]+$"
                title="Username must contain at least one letter" required>

                <input type="email" id="email" name="email" placeholder="Email"
                pattern="^[a-zA-Z0-9][a-zA-Z0-9._%+-]*[a-zA-Z0-9]@gmail\.com$"
                title="Please enter a valid Gmail address (e.g., example@gmail.com). Must end with @gmail.com"
                oninput="validateGmail(this)" required>

                <input type="password" id="password" name="password" placeholder="Password" required>
                <input type="text" id="mobilenumber" name="mobilenumber" placeholder="Mobile Number" 
                       pattern="[1-9]{1}[0-9]{9}" title="10 digits" required>
                <textarea id="address" name="address" placeholder="Address" required></textarea>
                <input type="text" id="pincode" name="pincode" placeholder="Pincode" 
                       pattern="[1-9]{1}[0-9]{5}" title="6 digits" required>
                <button type="submit" name="submit">Sign Up</button>
                <div class="error-message">
                    <?php echo $error_message ? htmlspecialchars($error_message) : ''; ?>
                </div>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <script>
function validateGmail(input) {
    input.value = input.value.toLowerCase();
    const domain = input.value.split('@')[1];
    
    if (domain && domain !== 'gmail.com') {
        input.setCustomValidity('Email must end with @gmail.com');
    } else {
        input.setCustomValidity('');
    }
}
</script>
</body>
</html>
