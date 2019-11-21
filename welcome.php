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
                    </div>
                </div>
            </nav>
            <div class="sidebar">
                <input type="text" name="message_search" placeholder="Search Message...">
            </div>

            <div class="main">  
                <h1>Hello, <?php echo $_SESSION['login_username']; ?></h1>
            </div> 

            <div class="messagebar">
                <input type="text" name="message" placeholder="Type Your Message...">
            </div>
        </div>
    
    </body>

</html>