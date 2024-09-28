<?php 

include "config.php";

session_start();

include "getAllUsers.php";
$users = getAllUsers($conn);
// print_r($users);

$message = "";
if(isset( $_SESSION['message']))
{
    $message= $_SESSION['message'];
    unset($_SESSION['message']);
}

?>

<html >
<head>
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #333;
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
        table, th, td{
            /* border: 1px solid; */
            border-bottom: 1px solid #DDD;
        }   
        th, td{
            padding: 20px;
            }
        th {
            background-color: #f2f2f2; /* Header background color */
        }
        table{
            width: 70%;
            margin: 0 auto 0 auto;
            margin: 50px auto;
            text-align: center;
            border-collapse: collapse;
            background-color:	#EEEDEB;
        }
        button{
            background-color: red;
            color: white; 
            border: none;
            font-size: 16px; 
            border-radius: 5px; 
        }
        button a{
            text-decoration: none;
            color: inherit; 
        }
    </style>
</head>
<body>
<div class="navbar">
        <a href="admin.php">Back</a>
    </div>
    <?php if($message){
        echo "<p class='message'>".$message."</p>";
    }
        ?>
<table>
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <!-- <td><?= $user['id'] ?></td> -->
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <button>
                        <a href="adminDeleteUsers.php?username=<?php echo $user['username'] ?>">Delete</a>
                        </button>

                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if(empty($users)): ?>
                <tr>
                    <td>No users found</td>
                </tr>
                <?php endif; ?>
        </tbody>
    </table>

</body>
</html>