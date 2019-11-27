<?php

require_once('mypdo.class.php');
require_once('user.class.php');


$mypdo = new MYPDO();
$user = new User();

$query = "SELECT username FROM Users WHERE username=:username";

if(isset($_POST['recipient'])){
    $recipient = $_POST['recipient'];
    $stmt = $mypdo->prep($query);
    $stmt->bindParam(':username',$recipient);
    $stmt->execute();
    $recipient_check = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($recipient)){
        if(strcmp($recipient_check,$recipient) == 0){
            echo "Recipient Exists";
        } 
        else{
            echo "Recipient Does Not Exist";
        }
            
    }
}

/*
if($result){
    echo "Recipient Exists";
}
else {
    echo "No Record Of Recipient";
}
*/
?>