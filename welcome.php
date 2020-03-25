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
        <link rel="icon" href="/otamot/otamot_no_title.png">
        <style>
            .thumbnail {
                height: 10%;
                min-height: 35px;
                width: 95%;
                display: block;
                margin: 0% auto;
                background-color: #F8F8F8;
                border: 2px solid #E8E8E8;
                border-radius: 10px;

                font-family: "Tahoma", Geneva, sans-serif; 
                font-size: 15px;
                cursor: pointer;
                outline-width: 0;
            }

            .user_messages {
                position: relative;
                margin: auto 0%;
                width: 30%;
                min-width: 200px;
                top: 0;
                left: 50%;
                border: 1px solid white;
                border-radius: 4px;
                padding: 10px;
                background-color: #F3E5AB;
            }

            .recip_messages {
                position: relative;
                margin: auto 2%;
                width: 30%;
                min-width: 200px;
                top: 0;
                left: 0;
                border: 1px solid white;
                border-radius: 4px;
                padding: 10px;
                background-color: #FFFFF0
            }
        </style>
        <script type="text/javascript">
            var login_username = "<?php echo $_SESSION['login_username']; ?>";
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="jquery.js"></script>

        <script> 

            window.onload=function(){
                var messageButton = document.getElementById("new_message_btn");
                var cancelButton = document.getElementById("cancel_button");

                messageButton.addEventListener("click", showModal);
                cancelButton.addEventListener("click", hideModal);


            }

            function showModal(){
                document.querySelector('.bg-modal').style.display = 'flex';
            }

            function hideModal(){
                document.querySelector('.bg-modal').style.display = 'none';
                document.querySelector('#recipient_input').value = "";
            }
        
	    </script>
    </head>

    <body>
        
        <div class="grid_container">
            
            <div class="sidebar">
                <div class="otamot_logo">
                    <img src="/otamot/otamot_logo.png" alt="otamot logo" width="90px" height="90px">
                </div>
                <div class="newMess_and_Search">
                    <button id="new_message_btn" class="new_message_button" type="submit" name="new_message"><b>New Message</b></button>
                    <input type="text" id="search_box" name="message_search" placeholder="Search Message...">
                </div>

                <div id="message_list" class="message_list"></div>

                <div class="links">
                    <a class="settings" href="#settings">Settings</a>
                    <a class="logout" href="logout.php">Sign Out</a>
                </div>
                
                 
            </div>

            <div class="main" id="main">  
                 <!-- - Div containers for holding the messages between a group chat or two individuals
                            will contain message body and date message was sent/created
                      - Before starting to add code to main message area we have to set the layout for how we want to organize the messages in welcome.css
                -->

                <div id="message_area" class="message_area"></div>
                

            </div> 

            <div class="messagebar" id="messagebar_container" style="display:none">
                <input type="text" id="message_input" name="message" placeholder="Type Your Message...">
                <input type="hidden" id="hidden_array" name="hidden_id_array">
                <button class="send_btn" name="send_message_submit" id="send_button"><b>Send</b></button>
            </div>
        </div>

        <!-- Modal Section -->
        <div class="bg-modal">

            <div class="new_message_container" id="new_message_container"  name="new_message_container">
                <input type="text" id="recipient_input" name="recipient" placeholder="Type Recipient's Username or Email...">
                <div id="feedback"></div>
                <button name="new_message_submit" id="chat_button">Chat</button>
                <button type="button" name="cancel_new_message" id="cancel_button">Cancel</button> 
            </div>

        </div>
    </body>

</html>

