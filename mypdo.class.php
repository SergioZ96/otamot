<?php
require('config.php');

class MyPDO{
	
		protected static $instance;
		protected $pdo;
		
		// automatically called on all newly created objects
		public function __construct(){
			
			$options = array(
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
						PDO::ATTR_EMULATE_PREPARES => FALSE,
						PDO::MYSQL_ATTR_FOUND_ROWS => TRUE
			);
			$this->pdo = new PDO(DSN, USERNAME, PASSWORD, $options);
		}
		
		// a classical static method to make it universally available
		public static function instance()
		{
			if (self::$instance === null)
			{
				self::$instance = new self; // same as self::$instance = new MyPDO();
			}
			return self::$instance;
		}

		public function prep($query)
		{
			return $this->pdo->prepare($query);
		}
		
}
?>
