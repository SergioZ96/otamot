<?php 

// starting a session necessary for each individual user trying to use the website
// starts a new session or resumes an existing session
session_start();

// ini_set() function allows a script to temporarily override a setting in PHP's configuration file.
// we are turning the display_errors setting to on, which is represented by the number 1. The default value is set to off
// as well as display_startup_errors, which is used to find errors during PHP's startup sequence
// error_reporting() sets which PHP errors are reported
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('mypdo.class.php');
require_once('user.class.php');
include('welcome_helper.php');

require 'vendor/autoload.php';

use \Mailjet\Resources;



# if user types otamotweb.com/otamot/login.php , then will redirect to just the main website. Disallows url with .php extension
/* if(strpos($_SERVER['REQUEST_URI'],'login.php') !== false) {
	header('Location: https://www.otamotweb.com');
}
 */
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
		$_POST["firstname"] = trim($_POST["firstname"]);
		$_POST["lastname"] = trim($_POST["lastname"]);
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
/*
function forgotPassword(User $user){

	// retrieve password from the form
	$user_email = $_POST['email_forpass'];
	$user_id = $user->getUserId($user_email);

	$selector = bin2hex(random_bytes(8));
	$token = random_bytes(32);

	// current URL
	$urlPath = "https://" .$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'];

	$url = sprintf('%sotamot/reset_password?%s', $urlPath, http_build_query(['selector' => $selector, 'validator' => bin2hex($token)]));

	// Token expiration
	$expires = new DateTime('NOW');
	$expires->add(new DateInterval('PT01H')); // 1 hour

	// Delete any existing tokens for this user
	$user->deleteToken($user_email);

	// Insert token into database
	$reset_info = array(
			'email' 	=> 	$user_email,
			'selector'	=>	$selector,
			'token'		=>	hash('sha256', $token),
			'expires'	=>	$expires->format('U')
	);
	$user->addToken($reset_info);

	$link = "<h3>Here is your password reset link:</br>";
	$link .= sprintf('<a href="%s">%s</a></p>', $url, $url);
	
	$mj = new \Mailjet\Client(API_KEY,SECRET_KEY,true,['version' => 'v3.1']);
	$body = [
	'Messages' => [
		[
		'From' => [
			'Email' => "sergiod.zurita@gmail.com",
			'Name' => "Sergio"
		],
		'To' => [
			[
			'Email' => $user_email,
			'Name' => "Sergio"
			]
		],
		'Subject' => "Reset Password on Otamot.",
		'TextPart' => "Greetings",
		'HTMLPart' => $link,
		'CustomID' => "AppGettingStartedTest"
		]
	]
	];
	$response = $mj->post(Resources::$Email, ['body' => $body]);
	// Read the response
	//$response->success() && var_dump($response->getData());
	
}
*/
?>

<!DOCTYPE html>
<html>
<head>
	<title>Otamot</title>

	<link rel="stylesheet" type="text/css" href="css/login.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript">
	
		$(document).ready(function() {
			$("#email_verifier").load('/otamot/welcome_helper.php').show();

			$(".forgetField").keyup(function() {
				var email_input = $(".forgetField").val();
				$.post('/otamot/welcome_helper.php', { email: email_input, type: "emailCheck"}, 
				function(data) {
					$("#email_verifier").html(data).show();

					if(!$('#email_verifier:contains("")').length > 0){
                    	document.getElementById("forgetPass_submit").disabled = true;
                	}
				});
			});
		});

		function formShow(a)
		{
			if(a==1){
				document.getElementById("register_form").style.display="block";
				document.getElementById("login_form").style.display="none";
				document.getElementById("forgetpass_form").style.display="none";
			} 
			else if (a==2) {
				document.getElementById("register_form").style.display="none";
				document.getElementById("login_form").style.display="block";
				document.getElementById("forgetpass_form").style.display="none";
			}
			else if(a==3) {
				document.getElementById("register_form").style.display="none";
				document.getElementById("login_form").style.display="none";
				document.getElementById("forgetpass_form").style.display="block";
			}
		}
	</script>
</head>
<body>
		<img src="otamot_logo.png" alt="otamot logo">
		<!--<h1>Otamot Welcomes You!</h1>-->
		<br>

		<button class="regButton" onclick="formShow(1)"><b>Register</b></button>
		<button class="logButton" onclick="formShow(2)"><b>Login</b></button>
		
		<!-- Registration -->
		<div class="container">
			<form id="register_form" method="POST">
				<input type="text" name="firstname" placeholder="First Name" required><br>
				<input type="text" name="lastname" placeholder="Last Name" required><br>
				<input type="text" name="username" placeholder="Username" required><br>
				<input type="text" name="email" placeholder="Email" required><br>
				<input type="password" name="password" placeholder="Password" required><br>
				<input type="password" name="conpassword" placeholder="Confirm Password" required><br>
				<input type="submit" value="Submit" name="reg_submit">
				
			</form>
		</div>

		<!-- Login -->
		<div class="container">
			<form id="login_form" style="display:none" method="POST">	
				<input type="text" name="login_username" placeholder="Username or Email" required><br>
				<input type="password" name="login_password" placeholder="Password" required><br>
				<input type="submit" value="Submit" name="login_submit">
			</form>
		</div>

		<!-- Forgot Password -->
		<div class="container">
			<form id="forgetpass_form" style="display:none" method="POST">
				<input type="text" class="forgetField" name="email_forpass" placeholder="Enter your email..." required>
				<div id="email_verifier"></div>
				<input type="submit" id="forgetPass_submit" value="Submit" name="forgetPass_submit">
			</form>
		</div>

		<button class="forgotPassword" onclick="formShow(3)">Forgot Password?</button>

		<?php 

		$mypdo = new MyPDO();
		$user = new User();

		if(isset($_POST['reg_submit'])){

			registerUser($user);
		}

		if(isset($_POST['login_submit'])){
			loginUser($user);
		}

		if(isset($_POST['forgetPass_submit']) && !empty($_POST['email_forpass'])){
			//forgotPassword($user);
			//header("location: /otamot/reset_password");
			Echo 
			"<div class=container>
				<h1>A Password Reset Link Has Been Sent To Your Email!</h1>
			</div>";
		}
		?>
</body>
</html>
