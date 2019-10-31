<?php
//include 'login.php';
// session_start() is needed to access the $_SESSION array to obtain its values
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Welcome</title>
    </head>

    <body>
        <h1>Hello, <?php echo $_SESSION['login_username']; ?></h1>
        <form action="logout.php" method="post">
            <input type="submit" value="Sign Out" name="sign_out"/>
        </form>
    </body>

</html>