<?php
/*
session_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
*/
require_once('mypdo.class.php');
require_once('user.class.php');

//require('config.php');

$selector = filter_input(INPUT_GET, 'selector');
$validator = filter_input(INPUT_GET, 'validator');

if(false !== ctype_xdigit($selector) && false !== ctype_xdigit($validator)) :
?>
    <div class="container">
        <form method="post">
            <input type="hidden" name="selector" value="<?php echo $selector; ?>">
            <input type="hidden" name="validator" value="<?php echo $validator; ?>">
            <input type="password" class="text" name="password" placeholder="Enter your new password" required>
            <input type="password" class="text" name="confirm_password" placeholder="Re-Enter your password" required>
            <input type="submit" class="submit" value="Submit" name="changepass_submit">
        </form>
        <p><a href="https://www.otamotweb.com">Login Here</a></p>
    </div>

<?php endif; ?>
<?php

//check if passwords match
if($_POST['password'] != $_POST['confirm_password']){
    echo 'Passwords do not match';
}

// (hash+salt)ing passwords and adding new user credentials to the database
if($_POST['changepass_submit']) {
    $hash = password_hash($_POST["password"],PASSWORD_DEFAULT);
    
    $user = new User();
    $update = $user->changePassword($selector,$validator,$hash);
    //$user->changePassword($selector,$validator,$hash);
    
    if ($update){
        echo "Your password was updated!";
        session_destroy();
    }
    else{
        echo "Could not update your password. Something is wrong with the system";
    }
    
}

?>