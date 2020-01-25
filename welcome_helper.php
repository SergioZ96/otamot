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

    $group_name = NULL;
    $user_ID = $user->getUserId($_SESSION['login_username']);
    $recipient_ID = $user->getUserId($_POST['data']);
    $chat_status = $user->chatExists($user_ID, $recipient_ID);

   // If a chat exists... 
    if($chat_status){
	    $group_ID = $chat_status;
        $parent_message_id = $user->lastMessage($group_ID);
	    $id_array = array($group_ID, $user_ID, $recipient_ID, $parent_message_id);
	    echo json_encode($id_array);
    }
    // Otherwise, a new group is created
    else{
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
    var_dump($id_array);
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
// Function needed to load chats between two users. Will also be working with jQuery
function loadChat(User $user){
	
    /* */
    

}

// Added a sendMessage condition
if(isset($_POST["type"])){
    $type = $_POST["type"];
    switch($type){
        case "recipientCheck":
            recipCheck($mypdo);
            break;
        case "groupChat":
            groupChat($user);
            break;
        case "sendMessage":
            sendMessage($user);
	    break;
	case "loadChat":
	    loadChat($user);
	    break;
    }
}

?>
