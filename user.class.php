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

			$this->stmt = $this->db->prep("INSERT INTO `Users` (username, password, first, last, email) VALUES (:username, :password, :first, :last, :email)");
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

		$this->stmt = $this->db->prep("SELECT `password`, `username` FROM `Users` WHERE username=:username1 OR email=:username2");
		
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

		$this->stmt = $this->db->prep("SELECT `username` FROM `Users` WHERE username=:username");
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

		$this->stmt = $this->db->prep("SELECT `email` FROM `Users` WHERE email=:email");
		$this->stmt->bindParam(':email', $email);
		$this->stmt->execute();
		$email_check = $this->stmt->fetch(PDO::FETCH_ASSOC);

		//comparing given email to what was fetched from the database
		if($email == $email_check["email"]){
			return true;
		}

		return false;
	}

	public function getId($username){


		$this->stmt = $this->db->prep("SELECT `id` FROM `Users` WHERE username=:username1 or email=:username2");
		$this->stmt->bindParam(':username1',$username);
		$this->stmt->bindParam(':username2',$username);
		$this->stmt->execute();
		$id_result = $this->stmt->fetch(PDO::FETCH_ASSOC);
		return $id_result["id"];

	}

	public function addGroup($name){
		
		$date = date("Y-m-d H:i:s");

		$this->stmt = $this->db->prep("INSERT INTO `Group` (name, create_date) VALUES (:name, :createdate)");
		$this->stmt->bindParam(':name', $name);
		$this->stmt->bindParam(':createdate', $date);
		$this->stmt->execute();
		
		// Retrieves the last inserted id immediately from the Group table
		// Note: when using LAST_INSERT_ID(), it only gets the last inserted id from the whole database connection
		//       and not just from a specific table
		$this->stmt = $this->db->prep("SELECT MAX( id ) FROM `Group`");
		$this->stmt->execute();
		$lastId = $this->stmt->fetch(PDO::FETCH_ASSOC);
		var_dump($lastId);
		return $lastId["MAX( id )"];
		
	}

	public function addUserGroup($id_array){

		$group_ID = $id_array[0];
		$user_ID = $id_array[1];
		$recipient_ID = $id_array[2];

		/*
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);

		$this->stmt = $this->db->prep("INSERT INTO `User_Group` (user_id, group_id) VALUES (:userID_1, :groupID_1);
										INSERT INTO `User_Group` (user_id, group_id) VALUES (:userID_2, :groupID_2");

		$this->stmt->execute(array(
			':userID_1' => $user_ID,
			':groupID_1' => $group_ID,
			':userID_2' => $recipient_ID,
			':groupID_2' => $group_ID
		));
		*/

		$this->stmt = $this->db->prep("INSERT INTO `User_Group` (user_id, group_id) VALUES (:userID_1, :groupID_1)");
		$this->stmt->bindParam(':userID_1', $user_ID);
		$this->stmt->bindParam(':groupID_1', $group_ID);
		$this->stmt->execute();

		$this->stmt = $this->db->prep("INSERT INTO `User_Group` (user_id, group_id) VALUES (:userID_2, :groupID_2)");
		$this->stmt->bindParam(':userID_2', $recipient_ID);
		$this->stmt->bindParam(':groupID_2', $group_ID);
		$this->stmt->execute();

	}


}
	
?>