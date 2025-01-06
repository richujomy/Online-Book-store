<?php
session_start();
include "config.php";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get user details
$email = $_SESSION['email'];
$sql = "SELECT * FROM signup WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

include "getCart.php";
$carts = getCart($conn);
$totalPrice = 0;

if (empty($carts)) {
    echo "Your cart is empty.";
    exit;
}

foreach ($carts as $cart) {
    $totalPrice += $cart['price'] * $cart['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Payment</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins';
            background-color: #323643;
            color: #333;
            margin: 0;
        }
        h1 {
            text-align: center;
        }
        .payment-container {
            max-width: 600px;
            margin: 25px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .book-details {
            text-align: center;
        }
        button {
            padding: 10px 15px;
            font-size: 16px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .navbar {
            background-color: #28282B;
            color: #fff;
            padding: 30px;
            text-align: left;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
        }
        .navbar a:hover {
            background-color: #555;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* User info styles */
        .user-info {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .info-row {
            margin: 15px 0;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-display {
            margin-bottom: 10px;
        }
        .info-edit {
            display: none;
        }
        .info-edit.active {
            display: block;
        }
        .info-edit input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .edit-btn {
            background-color: #6c757d;
            padding: 5px 10px;
            margin-top: 5px;
        }
        .edit-btn:hover {
            background-color: #5a6268;
        }
        
        /* Error message style */
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        /* Payment methods style */
        .payment-methods {
            margin: 20px 0;
            padding: 15px;
            border: none;
        }
        .payment-methods label {
            display: block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="home.php">Back to Home</a>
</div>
<div class="payment-container">
    <h1>Proceed to Payment</h1>
    <div class="book-details">
        <h1>Total Price: ₹<?= number_format($totalPrice, 2) ?></h1>
        <button id="openModal">Proceed to Payment</button>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Shipping Information</h2>
        <form id="paymentForm" action="process_payment.php" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="total_price" value="<?= htmlspecialchars($totalPrice) ?>">
            
            <div class="user-info">
                <div class="info-row">
                    <div class="info-display" id="mobileDisplay">
                        <strong>Mobile:</strong> <?= htmlspecialchars($user['mobilenumber']) ?>
                    </div>
                    <div class="info-edit" id="mobileEdit">
                        <input type="text" name="mobile" id="mobile" value="<?= htmlspecialchars($user['mobilenumber']) ?>" 
                               oninput="validateMobile(this)">
                        <div class="error-message" id="mobileError"></div>
                    </div>
                    <button type="button" class="edit-btn" onclick="toggleEdit('mobile')">Edit</button>
                </div>
                
                <div class="info-row">
                    <div class="info-display" id="addressDisplay">
                        <strong>Address:</strong> <?= htmlspecialchars($user['address']) ?>
                    </div>
                    <div class="info-edit" id="addressEdit">
                        <input type="text" name="address" id="address" value="<?= htmlspecialchars($user['address']) ?>" 
                               oninput="validateAddress(this)">
                        <div class="error-message" id="addressError"></div>
                    </div>
                    <button type="button" class="edit-btn" onclick="toggleEdit('address')">Edit</button>
                </div>
                
                <div class="info-row">
                    <div class="info-display" id="pincodeDisplay">
                        <strong>PIN Code:</strong> <?= htmlspecialchars($user['pincode']) ?>
                    </div>
                    <div class="info-edit" id="pincodeEdit">
                        <input type="text" name="pincode" id="pincode" value="<?= htmlspecialchars($user['pincode']) ?>" 
                               oninput="validatePincode(this)">
                        <div class="error-message" id="pincodeError"></div>
                    </div>
                    <button type="button" class="edit-btn" onclick="toggleEdit('pincode')">Edit</button>
                </div>
            </div>

            <fieldset class="payment-methods">
                <legend>Select Payment Method</legend>
                <label>
                    <input type="radio" name="payment_method" value="credit_card" required> Credit Card
                </label>
                <label>
                    <input type="radio" name="payment_method" value="paypal"> PayPal
                </label>
            </fieldset>

            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</div>

<script>
var modal = document.getElementById("paymentModal");
var btn = document.getElementById("openModal");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
    modal.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function toggleEdit(field) {
    const displayElement = document.getElementById(`${field}Display`);
    const editElement = document.getElementById(`${field}Edit`);
    
    if (editElement.classList.contains('active')) {
        editElement.classList.remove('active');
        displayElement.style.display = 'block';
    } else {
        editElement.classList.add('active');
        displayElement.style.display = 'none';
    }
}

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId + 'Error');
    errorElement.textContent = message;
    errorElement.style.display = message ? 'block' : 'none';
    return !!message;
}

function validateMobile(input) {
    const value = input.value.trim();
    if (!value) {
        return showError('mobile', 'Mobile number is required');
    }
    if (!/^[1-9][0-9]{9}$/.test(value)) {
        return showError('mobile', 'Mobile number must be exactly 10 digits and not start with 0');
    }
    showError('mobile', '');
    return false;
}

function validateAddress(input) {
    const value = input.value.trim();
    if (!value) {
        return showError('address', 'Address is required');
    }
    if (value.length < 5) {
        return showError('address', 'Address must be at least 5 characters long');
    }
    showError('address', '');
    return false;
}

function validatePincode(input) {
    const value = input.value.trim();
    if (!value) {
        return showError('pincode', 'PIN code is required');
    }
    if (!/^\d{6}$/.test(value)) {
        return showError('pincode', 'PIN code must be exactly 6 digits');
    }
    showError('pincode', '');
    return false;
}

function validateForm() {
    let hasError = false;
    
    // Only validate fields that are currently being edited
    const mobileEdit = document.getElementById('mobileEdit');
    const addressEdit = document.getElementById('addressEdit');
    const pincodeEdit = document.getElementById('pincodeEdit');
    
    if (mobileEdit.classList.contains('active')) {
        hasError = validateMobile(document.getElementById('mobile')) || hasError;
    }
    
    if (addressEdit.classList.contains('active')) {
        hasError = validateAddress(document.getElementById('address')) || hasError;
    }
    
    if (pincodeEdit.classList.contains('active')) {
        hasError = validatePincode(document.getElementById('pincode')) || hasError;
    }
    
    return !hasError;
}
</script>
</body>
</html>