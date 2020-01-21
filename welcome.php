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
        <link rel="stylesheet" href="css/welcome.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <script> /* jQeury for checking if recipient exists to initiaite a chat */
            $(document).ready(function() {
                $('#feedback').load('welcome_helper.php').show();

                $('#recipient_input').keyup(function() {
                    var recipient_input = $("#recipient_input").val();
                    $.post('welcome_helper.php', { recipient: recipient_input, type: "recipientCheck" },
                    function(data,status){
                        $('#feedback').html(data).show();

                        // Responsible for disabling/enabling chat button
                        if(!$('#feedback:contains("Recipient Exists")').length > 0){
                            document.getElementById("chat_button").disabled = true;
                        }
                        else{
                            document.getElementById("chat_button").disabled = false;
                        }
                    });
                });
            });


            /* jQuery for taking the recipients name and adding it to Group and User_Group tables
                - also used to pass an array in JSON containing group id, user id, recipient id in that order as a hidden field to the message form */
            $(document).ready(function() {
                $('#chat_button').click(function() {
                    var recipient = $("#recipient_input").val();
                    $.post('welcome_helper.php', { data : recipient, type: "groupChat" }, 
                    function(data,status){
                        var id_array = data;
                        $("#hidden_array").val(id_array);

                        // Responsible for resetting new message container
                        if($('#feedback:contains("Recipient Exists")').length > 0){
                            document.getElementById("recipient_input").value = "";
                            document.getElementById("new_message_container").style.display = "none";
                            document.getElementById("feedback").innerHTML = "";
                        }
                    });
                });
            });

            // jQuery for replacing submission form when sending a message

            $(document).ready(function() {
                $('#send_button').click(function() {
                    var message = $("#message_input").val();
                    var id_array = $("#hidden_array").val();
                    $.post('welcome_helper.php', {message: message, id_array: id_array, type: "sendMessage"},
                    function(data){
                        $("#message_in_main").html(data).show();
                    });
                });
            });

           
        </script>

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
                <div class="message_list">
                </div>
            </div>

            <div class="main">  
                 <!-- - Div containers for holding the messages between a group chat or two individuals
                            will contain message body and date message was sent/created
                      - Before starting to add code to main message area we have to set the layout for how we want to organize the messages in welcome.css
                -->

                <div id="message_in_main"></div>

            </div> 

            <div class="messagebar" id="messagebar_container">
                <input type="text" id="message_input" name="message" placeholder="Type Your Message...">
                <input type="hidden" id="hidden_array" name="hidden_id_array">
                <button name="send_message_submit" id="send_button">Send</button>
            </div>
        </div>
    
    </body>

</html>

<?php
    
    //$user = new User();
    //$id_array = array();
    /* If the send button is pressed, we decode the JSON array and pass it to sendMessage function 
       which adds new fields to the Message and Message_Recipient tables
    */
    /*
    if(isset($_POST['send_button'])){
        echo $_POST['hidden_id_array'];
        $id_array = json_decode($_POST['hidden_id_array']);
        sendMessage($user, $id_array);
        
    }
    */
?>
