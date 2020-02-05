<?php

// session_start() is needed to access the $_SESSION array to obtain its values
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

# if user types otamotweb.com/otamot/welcome and user is not logged in (starting a session), then redirects to main website 
if(strpos($_SERVER['REQUEST_URI'],'welcome') !== false && !isset($_SESSION['login_username'])) {
	header('Location: https://www.otamotweb.com');
}

# if user types 'url'.php but also the user is currently logged in and already in the welcome page, it will just return welcome without .php extension
if(strpos($_SERVER['REQUEST_URI'],'welcome.php') !== false && isset($_SESSION['login_username'])) {
	header('Location: welcome');
}

require_once('mypdo.class.php');
require_once('user.class.php');
include('welcome_helper.php');

date_default_timezone_set('America/New_York');

// Need functions to handle inserting users to Group and User_Group tables upon starting a New Message


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Welcome</title>
        <link rel="stylesheet" href="css/welcome.css" type="text/css" />
        <style>
            .thumbnail {
                height: 15%;
                width: 100%;
                background-color: #F8F8F8;
                border: 2px solid #E8E8E8;
                font-family: "Tahoma", Geneva, sans-serif; 
                font-size: 15px;
                cursor: pointer;
            }

            .user_messages {
                position: relative;
                width: 300px;
                top: 0;
                left: 80%;
            }

            .recip_messages {
                position: relative;
                width: 300px;
                top: 0;
                left: 0;
            }
        </style>
        <script type="text/javascript">
            var login_username = "<?php echo $_SESSION['login_username']; ?>";
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="jquery.js"></script>

        <script> 
            function formShow(a)
            {
                if(a==1){
                    document.getElementById("new_message_container").style.display="block";
                    
                } 
                else if(a==2) {
                    document.getElementById("new_message_container").style.display="none";
                }

            }
        
	    </script>
    </head>

    <body>
        <div class="new_message_container" id="new_message_container" style="display:none" name="new_message_container">
            <input type="text" id="recipient_input" name="recipient" placeholder="Type Recipient's Username or Email...">
            <div id="feedback"></div>
            <button name="new_message_submit" id="chat_button">Chat</button>
            <button type="button" name="cancel_new_message" onclick="formShow(2);">Cancel</button> 
        </div>
        
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
                    <button id="new_message_button" type="submit" name="new_message" onclick="formShow(1);">New Message</button>
                    <input type="text" name="message_search" placeholder="Search Message...">
                </div>
                <div id="message_list" class="message_list">

                </div>
            </div>

            <div class="main">  
                 <!-- - Div containers for holding the messages between a group chat or two individuals
                            will contain message body and date message was sent/created
                      - Before starting to add code to main message area we have to set the layout for how we want to organize the messages in welcome.css
                -->

                <div id="message_area" class="message_area"></div>
                

            </div> 

            <div class="messagebar" id="messagebar_container" style="display:none">
                <input type="text" id="message_input" name="message" placeholder="Type Your Message...">
                <input type="hidden" id="hidden_array" name="hidden_id_array">
                <button name="send_message_submit" id="send_button">Send</button>
            </div>
        </div>
    
    </body>

</html>

