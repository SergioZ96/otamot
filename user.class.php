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
}
	
?>