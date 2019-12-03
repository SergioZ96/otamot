<?php

class User
{
	protected $db;
	protected $stmt;
	
	public function __construct()
	{
		$this->db = MyPDO::instance(); // passing the MyPDO instance which contains the PDO and assigning it to $this->db
	}
	
	public function addUser($inputArray){

		/*
			Args: array of user registration values
			Returns: 
			Function: Inserts new users into the Users table
		*/

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

		/*
			Args: array of user login values
			Returns: bool, true if password matches password associated with given username. Otherwise, false.
		*/

		$login_username = $inputArray[0];
		$login_password = $inputArray[1];

		$this->stmt = $this->db->prep("SELECT password, username FROM Users WHERE username=:username1 OR email=:username2");
		
		$this->stmt->bindParam(':username1', $login_username);
		$this->stmt->bindParam(':username2', $login_username);
		$this->stmt->execute();
		$user = $this->stmt->fetch(PDO::FETCH_ASSOC);

		if(password_verify($login_password, $user['password'])){
			return $user['username'];
		}
		
		return false;
	}

	public function username_available($username){

		/*
			Args: string (username)
			Returns: bool, false if usename is in the database (meaning not available for new user to register as). Otherwise true.

		*/

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

		/*
			Args: string (email)
			Returns: bool, true if the user exists. Otherwise false.
		*/

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

	public function addGroup($name = NULL){
		
		$date = date("Y-m-d H:i:s");

		if($name == NULL){
			$this->stmt = $this->db->prep("INSERT INTO Group (name, create_date) VALUES (:name, :createdate)");
			$this->stmt->bindParam(':name', NULL);
			$this->stmt->bindParam(':createdate', $date);
			$this->stmt->execute();
		}
		
		// Retrieves the last inserted id immediately from the Group table
		// Note: when using LAST_INSERT_ID(), it only gets the last inserted id from the whole database connection
		//       and not just from a specific table
		$this->stmt = $this->db->prep("SELECT LAST_INSERT_ID()");
		$this->stmt->execute();
		$lastId = $this->stmt->fetch(PDO::FETCH_ASSOC);
		return $lastId;
		
	}

	public function addUserGroup(){

			

	}


}
	
?>