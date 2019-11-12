<?php
//include 'login.php';
// session_start() is needed to access the $_SESSION array to obtain its values
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Welcome</title>
        <link rel="stylesheet" href="css/welcome.css">
    </head>

    <body>
        <div class="grid_container">
            <div class="topnav">
                <ul>
                    <li><a href="logout.php">Sign Out</a></li>
                        <!--<form action="logout.php" method="post">
                            <input type="submit" value="Sign Out" name="sign_out"/>
                        </form>--></li>
                    <li><a href="#settings">Settings</a></li>
                    <li><a href="#search">Search</a></li>
                    
                </ul>
            </div>

            <div class="main">
                <h1>Hello, <?php echo $_SESSION['login_username']; ?></h1>
            </div>

            <div class="messagebar">Messages</div>
            
            <div class="typebar"></div>
            
        </div>
    </body>

</html>