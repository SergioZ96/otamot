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
            <nav>
                <div class="navbar">
                    <h1>Otamot</h1>
                    <div class="links">
                        <a href="#search">Search</a>
                        <a href="#settings">Settings</a>
                        <a href="logout.php">Sign Out</a>
                                <!--<form action="logout.php" method="post">
                                    <input type="submit" value="Sign Out" name="sign_out"/>
                                </form>-->
                    </div>
                </div>
            </nav>
        </div>
        <!--
        <div class="main">
            <h1>Hello, <?php //echo $_SESSION['login_username']; ?></h1>
        </div>

        <div class="messagebar">Messages</div>
        
        <div class="typebar"></div>
        -->
    </body>

</html>