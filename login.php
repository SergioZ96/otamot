<?php

?>

<!DOCTYPE html>
<html>
<head>
	<title>Otamot</title>
	<!--<script>
		function regForm(){
			//create a form
			var rf = document.createElement("form");
			rf.setAttribute('method',"post");
			
			//create input elements (first, last, username, email, password, confirm password)
			var fname = document.createElement("input");
			fname.type = "text";
			fname.name = "firstname";

			var lname = document.createElement("input");
			lname.type = "text";
			lname.name = "lastname";
			
			var uname = document.createElement("input");
			uname.type = "text";
			uname.name = "username";

			var email = document.createElement("input");
			email.type = "text";
			email.name = "email";
		
			var password = document.createElement("input");
			password.type = "password";
			password.name = "password";
	
			var con_pass = document.createElement("input");
			con_pass.type = "password";
			con_pass.name = "conpassword";

			var submit = document.createElement("input");
			submit.type = "submit";
			submit.value = "Submit";

			// add all the elements to the form
			rf.appendChild(fname);
			rf.appendChild(lname);
			rf.appendChild(uname);
			rf.appendChild(email);
			rf.appendChild(password);
			rf.appendChild(con_pass);
			rf.appendChild(submit);

			// adding the form inside the body
			// $("body").append(f); using jQuery or
			document.getElementsByTagName('body')[0].appendChild(rf);
			
		}
	</script>
	-->

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

		<form id="register_form">
			<input type="text" name="firstname" required>
			<input type="text" name="lastname" required>
			<input type="text" name="username" required>
			<input type="text" name="email" required>
			<input type="password" name="password" required>
			<input type="password" name="conpassword" required>
			<input type="submit" value="Submit">
			
		</form>

		<form id="login_form">	
			<input type="text" name="login_username" required>
			<input type="password" name="login_password" required>
			<input type="submit" value="Submit">
		</form>
		<input type="submit" value="Register" onclick="regForm()">
		<input type="submit" value="Login">
		
		<!-- Homepage will have a welcoming title as well as two buttons to choose from: Register or Login
		
		<h1>SIMPLE LOGIN</h1>
		<form method="POST">
			Username <input type="text" name="username" class="text" autocomplete="off" required>
			Password <input type="password" name="password" class="text" required>
			<input type="Submit" name="submit" id="sub">
		</form>
		
		<h1>REGISTER</h1>
		<form method="POST">
			First Name: <input type="text" name="firstname" class="text" autocomplete="off" required>
			Last Name: <input type="text" name="lastname" class="text" autocomplete="off" required>
			Email: <input type="text" name="email" class="text" autocomplete="off" required>
			Password: <input type="password" name="password" class="text" required>
			<input type="Submit" name="submit" id="sub">
		</form>
		-->
	
</body>
</html>
