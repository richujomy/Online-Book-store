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
        table, th, td{
            border: 1px solid;
        }
        table{
            width: 70%;
            margin: 0 auto 0 auto;
            margin: 50px auto;
        }
        .message{
            text-align: center;
            color: red;
        }
    </style>
</head>
<body>
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
                        <a href="adminDeleteUsers.php?username=<?php echo $user['username'] ?>">Delete</a>
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