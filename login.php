<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require('initDB.php');

function registerUser(){
	$regVariables = array('firstname','lastname','username','email','password','conpassword');
	$hash = "";
	if(!array_diff($regVariables, array_keys($_POST))){
		
		//validate email 
		$email = $_POST["email"];
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			echo '<br>Invalid email format<br>';
		}
		
		//check if passwords match
		if($_POST['password'] == $_POST['conpassword']){
			echo 'Passwords match<br>';
		}
		else {
			echo 'Passwords do not match';
		}
		
		// (hash+salt)ing passwords
		$hash = crypt($_POST["password"],"$1$");
		
		$inputArray = array($_POST['username'], $hash, $_POST['firstname'], $_POST['lastname'], $_POST['email']);
		// next step is to create a new user in database
		addUser($db, $inputArray);
	}


}

function loginUser(){
	$username = "";
	$password = "";
	if(isset($_POST['login_username']) && isset($_POST['login_password'])){
		$username = $_POST['login_username'];
		$password = $_POST['login_password'];
		
		// check if the username and password exist in database
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Otamot</title>

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

		<button onclick="formShow(1)">Register</button>
		<button onclick="formShow(2)">Login</button>

		<form id="register_form" method="POST">
			First Name: <input type="text" name="firstname" required><br>
			Last Name: <input type="text" name="lastname" required><br>
			Username: <input type="text" name="username" required><br>
			Email: <input type="text" name="email" required><br>
			Password: <input type="password" name="password" required><br>
			Confirm Password: <input type="password" name="conpassword" required><br>
			<input type="submit" value="Submit" name="submit">
			
		</form>

		<form id="login_form" style="display:none" method="POST">	
			Username or Email: <input type="text" name="login_username" required><br>
			Password: <input type="password" name="login_password" required><br>
			<input type="submit" value="Submit" name="submit">
		</form>
		

		<?php 
		if(isset($_POST['submit'])){
			registerUser();
		}
		?>
</body>
</html>
