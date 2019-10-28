<?php
//include 'login.php';
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Welcome</title>
    </head>

    <body>
        <h1>Hello, <?php echo $_SESSION['login_username']; ?></h1>
    </body>

</html>