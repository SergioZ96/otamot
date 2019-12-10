<?php
//include 'login.php';
// session_start() is needed to access the $_SESSION array to obtain its values
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);


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
                    });
                });
            });


            /* jQuery for taking the recipients name and adding it to Group and User_Group tables */
            $(document).ready(function() {
                $('#chat_button').click(function() {
                    var recipient = $("#recipient_input").val();
                    $.post('welcome_helper.php', { data : recipient, type: "groupChat" }, 
                    function(data,status){
                        var id_array = data;
                        $("#hidden_array").val(id_array);
                    });
                });
            });

           
        </script>

        <script> 
		function formShow(a)
		{
			if(a==1){
				document.getElementById("new_message_container").style.display="block";
				
			} else {
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
                 
            </div> 

            <div class="messagebar">
                <form id="message_bar" method="POST">
                    <input type="text" name="message" placeholder="Type Your Message...">
                    <input type="hidden" id="hidden_array" name="hidden_id_array">
                    <input type="submit" name="send_button" value="Send">
                </form>
            </div>
        </div>
    
    </body>

</html>

<?php
    
    $user = new User();
    //$id_array = array();
    if(isset($_POST['send_button'])){
        $id_array = json_decode($_POST['hidden_id_array']);
        sendMessage($user, $id_array);
        
    }
    
    

    
?>