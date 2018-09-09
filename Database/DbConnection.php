<?php
/**
 * Created by PhpStorm.
 * User: dulo
 * Date: 9/9/18
 * Time: 12:34 AM
 */
require_once 'SystemComponents.php';

class DbConnection extends SystemComponents {

	private static $instance = null;
	private $conn;

	private function __construct($host, $dbName, $username, $password) {

		try {
			$this->conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}catch (PDOException $e) {
			echo $e->getMessage();
			die();
		}
	}

	public static function connectToDb() {
		$settings = SystemComponents::getSettings();

		if(DbConnection::$instance == null)
		{
			DbConnection::$instance = new DbConnection($settings['dbhost'], $settings['dbname'], $settings['dbusername'], $settings['dbpassword']);
		}

		return DbConnection::$instance->getConn();
	}

	/**
	 * @return mixed
	 */
	public function getConn() {
		return $this->conn;
	}
}