<?php

if(!isset($_SESSION)){
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('mypdo.class.php');
require_once('user.class.php');

date_default_timezone_set('America/New_York');


$mypdo = new MYPDO();
$user = new User();

function emailCheck($mypdo){
    $query = "SELECT email FROM Users WHERE email=:email";

    if(isset($_POST['email'])){
        $email = $_POST['email'];
        $stmt = $mypdo->prep($query);
        $stmt->bindParam(':email',$email);
        $stmt->execute();
        $email_check = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // if not empty
        if(!empty($email)){ 
            if(strcmp($email_check["email"],$email) == 0){ // if the strings are equal, which would be equal to 0
                echo "";
            } 
            else{
                echo "User does not exist";
            }
                
        }
    }
}


function recipCheck($mypdo){
    $query = "SELECT username FROM Users WHERE username=:username";

    if(isset($_POST['recipient'])){
        $recipient = $_POST['recipient'];
        $stmt = $mypdo->prep($query);
        $stmt->bindParam(':username',$recipient);
        $stmt->execute();
        $recipient_check = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // if not empty
        if(!empty($recipient)){ 
            if(strcmp($recipient_check["username"],$_SESSION["login_username"]) == 0){
                echo "Cannot send message to self";
            }
            elseif(strcmp($recipient_check["username"],$recipient) == 0){ // if the strings are equal, which would be equal to 0
                echo "Recipient Exists";
            } 
            else{
                echo "Recipient Does Not Exist";
            }
                
        }
    }
}

function groupChat(User $user){
    /*
        Args: User object
        Returns: no return value
        Function: inserts new records into Group and User_Group tables
    */

    
    $user_ID = $user->getUserId($_SESSION['login_username']);
    $recipient_ID = $user->getUserId($_POST['data']);
    $chat_status = $user->chatExists($user_ID, $recipient_ID);

   // If a chat exists... 
    if($chat_status){
	    $group_ID = $chat_status;
        $parent_message_id = $user->lastMessage($group_ID); // retrieves last message id
	    $id_array = array($group_ID, $user_ID, $recipient_ID, $parent_message_id);
	    echo json_encode($id_array);
    }
    // Otherwise, a new group is created
    else{
        $group_name = NULL;
	    $parent_message_id = NULL;
        $group_ID = $user->addGroup($group_name);
        $id_array = array($group_ID, $user_ID, $recipient_ID, $parent_message_id);
	    $user->addUserGroup($id_array);
	    echo json_encode($id_array);
    }

}
// Modified sendMessage to work with jQuery
function sendMessage(User $user){

    $id_array = json_decode($_POST['id_array']);
    $message_body = $_POST['message'];
    $group_ID = $id_array[0];
    $user_creator_ID = $id_array[1];
    $recipient_ID = $id_array[2];
    $parent_message_ID = $id_array[3];

    $result = $user->addMessage($user_creator_ID, $recipient_ID, $message_body, $group_ID, $parent_message_ID);

    if($result){
	    echo json_encode($message_body);
    }

}

function chatThumbs(User $user) {
    $user_id = $user->getUserid($_POST['login_username']);
    $chat_records = $user->recipRecord($user_id);
    echo json_encode($chat_records);
}

// Function needed to load chats between two users. Will also be working with jQuery
function loadChat(User $user){
    // Values needed to update hidden id array for sending messages
    $user_id = $user->getUserId($_SESSION['login_username']);
    $recip_id = $_POST['recip_id'];
    $group_id = $_POST['group_id'];
    $parent_message_id = $user->lastMessage($group_id);

    $id_array = array((int)$group_id, (int)$user_id, (int)$recip_id, (int)$parent_message_id);
    // contains the messages of a group/chat
    $chat_messages_info = $user->getMessages($_POST['group_id']);

    // necessary to json_encode id_array to maintain array format instead of it being a string
    $convo_data_array = array("id_array" => json_encode($id_array), "chat_messages" => $chat_messages_info);
    
    echo json_encode($convo_data_array);
}

// Function handler that works upon receiving type value from jQuery post
if(isset($_POST["type"])){
    $type = $_POST["type"];
    switch($type){
        case "emailCheck":
            emailCheck($mypdo);
            break;
        case "recipientCheck":
            recipCheck($mypdo);
            break;
        case "groupChat":
            groupChat($user);
            break;
        case "sendMessage":
            sendMessage($user);
	        break;
	    case "chatThumbs":
	        chatThumbs($user);
            break;
        case "loadChat":
            loadChat($user);
            break;
    }
}

?>
