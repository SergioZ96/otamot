<?php 
// ini_set() function allows a script to temporarily override a setting in PHP's configuration file.
// we are turning the display_errors setting to on, which is represented by the number 1. The default value is set to off
// as well as display_startup_errors, which is used to find errors during PHP's startup sequence
// error_reporting() sets which PHP errors are reported
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('mypdo.class.php');
require_once('user.class.php');


// starting a session necessary for each individual user trying to use the website
// starts a new session or resumes an existing session
session_start();


# if user types otamotweb.com/otamot/login.php , then will redirect to just the main website. Disallows url with .php extension
if(strpos($_SERVER['REQUEST_URI'],'login.php') !== false) {
	header('Location: https://www.otamotweb.com');
}

function registerUser(User $user){
	/*
		Args: User object
		Returns: no return value
		Function: verifies if username is available, if user already exists, if email is in correct format and if passwords can be confirmed upon registration.
				  If credentials check out, it will add/create new user to the Users table in the database by calling the User objects class method 'addUser'
	*/


	$regVariables = array('firstname','lastname','username','email','password','conpassword');
	$hash = "";


	//makes sure that POST array contains all correct key fields
	//array with length > 0 returns TRUE
	//if the array has 0 elements, it is considered to be false. the negation unary operator then turns the empty array to be true
	if(!array_diff($regVariables, array_keys($_POST))){

		// Remove beginning and trailing whitespaces
		$_POST["first"] = trim($_POST["first"]);
		$_POST["last"] = trim($_POST["last"]);
		$_POST["username"] = trim($_POST["username"]);
		$_POST["email"] = trim($_POST["email"]);
		$email = $_POST["email"];


		// Disallow white spaces in usernames
		if(preg_match('/\s/',$_POST["username"])){
			echo '<br>Username cannot contain white spaces<br>';
		}

		// check if username is available by checking in database
		elseif(($user->username_available($_POST["username"])) == false){
			echo '<br>Username is not available<br>';
		}

		// check if user already exists by running email through database
		elseif(($user->userExists($_POST["email"])) == true){
			echo '<br>User already exists with email<br>';
		}
			
		//validate email 
		elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			echo '<br>Invalid email format<br>';
		}
		
		//check if passwords match
		elseif($_POST['password'] != $_POST['conpassword']){
			echo 'Passwords do not match';
		}
		
		// (hash+salt)ing passwords and adding new user credentials to the database
		else {
			$hash = password_hash($_POST["password"],PASSWORD_DEFAULT);
			
			$inputArray = array($_POST['username'], $hash, $_POST['firstname'], $_POST['lastname'], $_POST['email']);
                	// next step is to create a new user in database
                	$user->addUser($inputArray);
		}

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

	//if there is a login_username and login_password field then it will return an empty array being false. 
	//However, negation unary operator turns it to true
	if(!array_diff($logVariables, array_keys($_POST))){

		$inputArray = array($_POST["login_username"], $_POST["login_password"]);
		$login_result = $user->checkUser($inputArray);
		if($login_result != false){
			//username is added to the $_SESSION array
			$_SESSION['login_username'] = $login_result;

			// will jump to the welcome page
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
