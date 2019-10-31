<?php

class User
{
	protected $db;
	protected $stmt;
	
	public function __construct()
	{
		$this->db = MyPDO::instance();
	}
	
	public function addUser($inputArray){

			$username = $inputArray[0];
			$password = $inputArray[1];
			$firstname = $inputArray[2];
			$lastname = $inputArray[3];
			$email = $inputArray[4];

			$this->stmt = $this->db->prep("INSERT INTO Users (username, password, first, last, email) VALUES (:username, :password, :first, :last, :email)");
			$this->stmt->bindParam(':username', $username);
			$this->stmt->bindParam(':password', $password);
			$this->stmt->bindParam(':first', $firstname);
			$this->stmt->bindParam(':last', $lastname);
			$this->stmt->bindParam(':email', $email);
			
			
			
			$this->stmt->execute();
			return $this->stmt;
	}

	public function checkUser($inputArray){

		$login_username = $inputArray[0];
		$login_password = $inputArray[1];

		$this->stmt = $this->db->prep("SELECT password FROM Users WHERE username=:username1 OR email=:username2");
		
		$this->stmt->bindParam(':username1', $login_username);
		$this->stmt->bindParam(':username2', $login_username);
		$this->stmt->execute();
		$user = $this->stmt->fetch(PDO::FETCH_ASSOC);

		if($login_password == $user['password']){
			return true;
		}
		else {
			return false;
		}
	}

	public function username_available($username){

		$this->stmt = $this->db->prep("SELECT username FROM Users WHERE username=:username");
		$this->stmt->bindParam(':username', $username);
		//NOTE:
		// execute returns true if prepared statement executes. Doesn't matter if values are not found in the database
		$this->stmt->execute();
		$username_check = $this->stmt->fetch(PDO::FETCH_ASSOC);
		
		//comparing given username to what was fetched from the database
		if($username == $username_check["username"]){
			return false;
		}

		return true;
	}

	public function userExists($email){

		$this->stmt = $this->db->prep("SELECT email FROM Users WHERE email=:email");
		$this->stmt->bindParam(':email', $email);
		$this->stmt->execute();
		$email_check = $this->stmt->fetch(PDO::FETCH_ASSOC);

		//comparing given email to what was fetched from the database
		if($email == $email_check["email"]){
			return true;
		}

		return false;
	}

}
	
?>