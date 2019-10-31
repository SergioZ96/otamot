<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

//include 'initDB.php';
require_once('mypdo.class.php');
require_once('user.class.php');

session_start();


function registerUser(User $user){
	$regVariables = array('firstname','lastname','username','email','password','conpassword');
	$hash = "";

	if(($user->username_available($_POST["username"])) == true){
		echo '<br>Username is not available<br>';
	}

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
		$hash = crypt($_POST["password"],"$1$");
		
		$inputArray = array($_POST['username'], $hash, $_POST['firstname'], $_POST['lastname'], $_POST['email']);
		// next step is to create a new user in database
		$user->addUser($inputArray);
	}


}

function loginUser(User $user){
	$logVariables = array('login_username','login_password');
	$hash = "";
	if(!array_diff($logVariables, array_keys($_POST))){

		// hashed login password to match password stored in database
		$hash = crypt($_POST["login_password"],"$1$");

		$inputArray = array($_POST["login_username"], $hash);
		$login_result = $user->checkUser($inputArray);
		if($login_result){
			//echo "Welcome" . $_POST['login_username'];
			$_SESSION['login_username'] = $_POST['login_username'];

			header("location: welcome.php");
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

	<link rel="stylesheet" href="css/login.css">

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
