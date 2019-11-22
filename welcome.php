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
                <div class="newMess_and_Search">
                    <input type="submit" value="New Message" name="new_message">
                    <input type="text" name="message_search" placeholder="Search Message...">
                </div>
                <div class="message_list">
                </div>
            </div>

            <div class="main">  
                 
            </div> 

            <div class="messagebar">
                <form id="message_bar" method="POST">
                    <input type="text" name="message" placeholder="Type Your Message...">
                    <input type="submit" name="send_button" value="Send">
                </form>
            </div>
        </div>
    
    </body>

</html>