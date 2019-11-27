<?php
//include 'login.php';
// session_start() is needed to access the $_SESSION array to obtain its values
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);


require_once('mypdo.class.php');
require_once('user.class.php');

//New Message button is supposed to make the pop up form show in the center of the webpage
//  - however it will always be hidden

function initChat(User $user){
    
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Welcome</title>
        <link rel="stylesheet" href="css/welcome.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#feedback').load('user_extended.php').show();

                $('#recipient_input').keyup(function() {
                    var recipient_input = $("#recipient_input").val();
                    $.post('user_extended.php', { recipient: recipient_input },
                    function(data,status){
                        $('#feedback').html(data).show();
                    });
                });
            });
           
        </script>

        <script>
		function formShow(a)
		{
			if(a==1){
				document.getElementById("new_message_form").style.display="block";
				
			} else {
				document.getElementById("new_message_form").style.display="none";
				
			}
		}
	</script>
    </head>

    <body>
        <div class="new_message_container">
            <form id="new_message_form" style="display:none" name="form" method="post">
                <input type="text" id="recipient_input" name="recipient" placeholder="Type Recipient's Username or Email...">
                <div id="feedback"></div>
                <input type="submit" name="new_message_submit" value="Chat">
                <button type="button" name="cancel_new_message" onclick="formShow(2);">Cancel</button> 
            </form>
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