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

	public function getUserId($username){

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
		return $lastId["MAX( id )"];
		
	}

	public function addUserGroup($id_array){

		$group_ID = $id_array[0];
		$user_ID = $id_array[1];
		$recipient_ID = $id_array[2];

		$this->stmt = $this->db->prep("INSERT INTO `User_Group` (user_id, group_id) VALUES (:userID_1, :groupID_1)");
		$this->stmt->bindParam(':userID_1', $user_ID);
		$this->stmt->bindParam(':groupID_1', $group_ID);
		$this->stmt->execute();

		$this->stmt = $this->db->prep("INSERT INTO `User_Group` (user_id, group_id) VALUES (:userID_2, :groupID_2)");
		$this->stmt->bindParam(':userID_2', $recipient_ID);
		$this->stmt->bindParam(':groupID_2', $group_ID);
		$this->stmt->execute();

	}

	public function addMessage($creator_id, $recipient_id, $message_body, $group_id, $parent_message_id){

		$date = date("Y-m-d H:i:s");
		
		$this->stmt = $this->db->prep("INSERT INTO `Message` (creator_id, message_body, create_date, parent_message_id) VALUES (:creator_id, :message_body, :create_date, :parent_message_id)");
		$this->stmt->execute(array(
			':creator_id' => $creator_id,
			':message_body' => $message_body,
			':create_date' => $date,
			':parent_message_id' => $parent_message_id
		));
		$this->stmt = $this->db->prep("SELECT LAST_INSERT_ID()");
		$this->stmt->execute();
		$lastMessage = $this->stmt->fetch(PDO::FETCH_ASSOC);
		$message_id = $lastMessage["LAST_INSERT_ID()"];
		//$this->stmt = $this->db->prep("SELECT * FROM `Message`");
		//$this->stmt->execute();
		//$message_record = $this->stmt->fetch(PDO::FETCH_ASSOC);
		//$message_id = $message_record["id"];

		$this->stmt = $this->db->prep("INSERT INTO `Message_Recipient` (recipient_id, recipient_group_id, message_id) VALUES (:recipient_id, :recipient_group_id, :message_id)");
		$execution_result = $this->stmt->execute(array(
			':recipient_id' => $recipient_id,
			':recipient_group_id' => $group_id,
			':message_id' => $message_id
		));

		return $execution_result;

		// how to update parent message id
	}
	// Needs to return the last message id of a conversation/chat between two users
	public function lastMessage($group_id){
		
		$this->stmt = $this->db->prep("SELECT MAX( id ) FROM `Message` WHERE `id` IN (SELECT MAX( message_id ) FROM `Message_Recipient` WHERE recipient_group_id=:group_id)");
		$this->stmt->bindParam(':group_id', $group_id);
		$this->stmt->execute();
		$last_message_id = $this->stmt->fetch(PDO::FETCH_ASSOC);
		return $last_message_id["MAX( id )"];
	}

	public function chatExists($user_id_one, $user_id_two){
		
		$this->stmt = $this->db->prep("SELECT `group_id` FROM `User_Group` WHERE user_id=:user_id_one and `group_id` in (SELECT `group_id` FROM `User_Group` WHERE user_id=:user_id_two)");
		$this->stmt->bindParam(':user_id_one', $user_id_one);
		$this->stmt->bindParam(':user_id_two', $user_id_two);
		$this->stmt->execute();
		$groupId = $this->stmt->fetch(PDO::FETCH_ASSOC);

		if(empty($groupId["group_id"])){
			return false;
		}
		return $groupId["group_id"];
	}

	// Responsible for retrieving messages from a conversation/chat to load
	public function getMessages($group_id){
		// Query needs to get all message_id's that have the same recipient_group_id's from message_recipient table
		$this->stmt = $this->db->prep("SELECT `creator_id`, `message_body`, `create_date` FROM `Message` WHERE id IN (SELECT `message_id` FROM `Message_Recipient` WHERE recipient_group_id=:group_id)");
		$this->stmt->bindParam(':group_id', $group_id);
		$this->stmt->execute();
		$messages = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		return $messages;
	}

	# Necessary for setting up chat thumbnails
	public function recipRecord($user_id){
		// This function returns an array of recipients names, recipients id and recipients group id of all existing chats
		$this->stmt = $this->db->prep("SELECT `username`,`user_id`,`group_id` 
										FROM `Users`,`User_Group` WHERE `group_id` IN 
											(SELECT `group_id` FROM `User_Group` WHERE user_id=:user_id) 
											and user_id!=:user_id_two and User_Group.user_id = Users.id;");
		$this->stmt->bindParam(':user_id', $user_id);
		$this->stmt->bindParam(':user_id_two', $user_id);
		$this->stmt->execute();
		$chat_recip_info = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		return $chat_recip_info;
	}


}
	
?>
