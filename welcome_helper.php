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
            if(strcmp($recipient_check["username"],$recipient) == 0){ // if the strings are equal, which would be equal to 0
                echo "Recipient Exists";
            } 
            else if(strcmp($recipient_check["username"],$_SESSION["login_username"]) == 0){
                echo "Cannot send message to self";
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

    if(is_null($group_name)){
        $group_ID = $user->addGroup($group_name);
        $user_ID = $user->getUserId($_SESSION['login_username']);
        $recipient_ID = $user->getUserId($_POST['data']);

        $id_array = array($group_ID, $user_ID, $recipient_ID);
        $user->addUserGroup($id_array);
        echo json_encode($id_array);
    }
}

function sendMessage(User $user, $id_array){

    $group_ID = $id_array[0];
    $user_creator_ID = $id_array[1];
    $recipient_ID = $id_array[2];
    $message_body = $_POST['message'];

    $message_ID = $user->addMessage($user_creator_ID, $message_body);

    array_push($id_array, $message_ID);
    $result = $user->addMessageRecipient($id_array);

    if($result)
    {
        var_dump($message_body);
    }
}

function loadChat($username){
    
}


if(isset($_POST["type"])){
    $type = $_POST["type"];
    switch($type){
        case "recipientCheck":
            recipCheck($mypdo);
            break;
        case "groupChat":
            groupChat($user);
            break;
    }
}
/*
switch($type){
    case "recipientCheck":
        recipCheck($mypdo);
        break;
    case "groupChat":
        groupChat($user);
        break;
}
*/



?>