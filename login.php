<?php

?>

<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
</head>
<body>
	<div id="main">
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
	</div>
</body>
</html>
