<?php

require_once('mypdo.class.php');


$mypdo = new MYPDO();


$type = $_POST["type"];


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
            if(strcmp($recipient_check,$recipient) == 0){ // if the strings are equal, which would be equal to 0
                echo "Recipient Exists";
            } 
            else{
                echo "Recipient Does Not Exist";
            }
                
        }
    }
}

switch($type){
    case "recipientCheck":
        recipCheck($mypdo);
        break;
}




?>