<?php
// ini_set() function allows a script to temporarily override a setting in PHP's configuration file.
// we are turning the display_errors setting to on, which is represented by the number 1. The default value is set to off
// as well as display_startup_errors, which is used to find errors during PHP's startup sequence
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('mypdo.class.php');
require_once('user.class.php');

// starting a session necessary for each individual user trying to use the website
session_start();


function registerUser(User $user){
	/*
		Args: User object
		Returns: no return value
		Function: verifies if username is available, if user already exists, if email is in correct format and if passwords can be confirmed upon registration.
				  If credentials check out, it will add/create new user to the Users table in the database by calling the User objects class method 'addUser'
	*/


	$regVariables = array('firstname','lastname','username','email','password','conpassword');
	$hash = "";

	// check if username is available by checking in database
	if(($user->username_available($_POST["username"])) == false){
		echo '<br>Username is not available<br>';
	}

	// check if user already exists by running email through database
	elseif(($user->userExists($_POST["email"])) == true){
		echo '<br>User already exists with email<br>';
	}

	elseif(!array_diff($regVariables, array_keys($_POST))){
		
		//validate email 
		$email = $_POST["email"];
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			echo '<br>Invalid email format<br>';
		}
		
		//check if passwords match
		if($_POST['password'] == $_POST['conpassword']){
			
		}
		else {
			echo 'Passwords do not match';
		}
		
		// (hash+salt)ing passwords
		$hash = password_hash($_POST["password"],PASSWORD_DEFAULT);
		
		$inputArray = array($_POST['username'], $hash, $_POST['firstname'], $_POST['lastname'], $_POST['email']);
		// next step is to create a new user in database
		$user->addUser($inputArray);
	}


}

function loginUser(User $user){
	/*
		Args: User object
		Returns: no return value
		Function: checks the user login credentials to verify presence in the database through password and user comparison by calling the User objects class method 'checkUser'
				  If the user credentials can be verified within the database, then the site will go to the welcome page
	*/

	$logVariables = array('login_username','login_password');
	$hash = "";
	if(!array_diff($logVariables, array_keys($_POST))){

		// hashed login password to match password stored in database
		//$hash = crypt($_POST["login_password"],"$1$");

		$inputArray = array($_POST["login_username"], $_POST["login_password"]);
		$login_result = $user->checkUser($inputArray);
		if($login_result != false){
			//username is added to the $_SESSION array
			$_SESSION['login_username'] = $login_result;

			// will jump the welcome page
			header("location: /otamot/welcome");
		}
		else {
			echo "Invalid username/password";
		}
	}
		
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Otamot</title>

	<link rel="stylesheet" type="text/css" href="/otamot/css/login.css">

	<script>
		function formShow(a)
		{
			if(a==1){
				document.getElementById("register_form").style.display="block";
				document.getElementById("login_form").style.display="none";
			} else {
				document.getElementById("register_form").style.display="none";
				document.getElementById("login_form").style.display="block";
			}
		}
	</script>
</head>
<body>
		<h1>Otamot Welcomes You!</h1>
		<br>

		<button class="regButton" onclick="formShow(1)">Register</button>
		<button class="logButton" onclick="formShow(2)">Login</button>

		<div class="container">
			<form id="register_form" method="POST">
				First Name: <input type="text" name="firstname" required><br>
				Last Name: <input type="text" name="lastname" required><br>
				Username: <input type="text" name="username" required><br>
				Email: <input type="text" name="email" required><br>
				Password: <input type="password" name="password" required><br>
				Confirm Password: <input type="password" name="conpassword" required><br>
				<input type="submit" value="Submit" name="reg_submit">
				
			</form>
		</div>

		<div class="container">
			<form id="login_form" style="display:none" method="POST">	
				Username or Email: <input type="text" name="login_username" required><br>
				Password: <input type="password" name="login_password" required><br>
				<input type="submit" value="Submit" name="login_submit">
			</form>
		</div>

		<?php 

		$mypdo = new MyPDO();
		$user = new User();

		if(isset($_POST['reg_submit'])){

			registerUser($user);
		}

		if(isset($_POST['login_submit'])){
			loginUser($user);
		}
		?>
</body>
</html>
