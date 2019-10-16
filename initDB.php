<?php
#connecting to DB

#turning error reporting on
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');

$connect_string = "mysql:host=$host;dbname=$database;charset=utf8mb4";

function createTable($db){
	$query = "create table if not exists `Users`(
				`id` INT AUTO_INCREMENT NOT NULL,
				`username` VARCHAR(40) NOT NULL UNIQUE,
				`password` VARCHAR(200) NOT NULL UNIQUE,
				`first` VARCHAR(20) NOT NULL,
				`last` VARCHAR(20) NOT NULL,
				`email` VARCHAR(40) NOT NULL UNIQUE,
				PRIMARY KEY (`id`)
				) CHARACTER SET utf8 COLLATE utf8_general_ci";
	$stmt = $db->prepare($query);
	$r = $stmt->execute();
	return $r;
}

function addUser($db,$inputArray){
	$stmt = $db->prepare("INSERT INTO Users (username, password, first, last, email) VALUES (:username, :password, :first, :last, :email)");
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':password', $password);
	$stmt->bindParam(':first', $firstname);
	$stmt->bindParam(':last', $lastname);
	$stmt->bindParam(':email', $email);
	
	$username = $inputArray[0];
	$password = $inputArray[1];
	$firstname = $inputArray[2];
	$lastname = $inputArray[3];
	$email = $inputArray[4];
	
	$stmt->execute();

}

try{
	$db = new PDO($connect_string, $username, $password);
	$result = createTable($db);
				
} catch(Exception $e) {
	echo $e->getMessage();
	exit("Something went wrong");
}
?>
