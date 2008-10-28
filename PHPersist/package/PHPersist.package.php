<?php

/* **********************************************************************************
 *
 *	PHPersist
 *
 *	A persistence layer loosly inspired by Java's Hibernate.  The goal of this
 *	package is to create a more useful mapping between a business entity and the
 *	persistance layer used (ie: the database).
 *
 *	@package	PHPersist
 *	@version	0.0.1-alpha
 *	@author		Keith Framnes <keith.framnes@gmail.com>
 *
 * *********************************************************************************/

/* **********************************************************************************
 *
 *	PHPersist_Config
 *
 *	Configuration singleton for storing information like database credentials.
 *
 * *********************************************************************************/
class PHPersist_Config {

	/* ******************************************************************************
	 *
	 *  Factory Pattern pieces
	 *  - public static instance of the configuration class
	 *  - public static factory method for return singleton instance
	 *
	 * *****************************************************************************/
	public static $instance = null;

	public static function getInstance() {

		if(self::$instance == null) {
			self::$instance = new PHPersist_Config();
		}
		return self::$instance;
	
	}

	/* ******************************************************************************
	 *	
	 *	Internal mechanism for setting and getting configurations for PHPersist
	 *
	 * *****************************************************************************/
	private $config;
	private $expected = array();

	private function __construct() {
		$this->expected = array("db_host","db_user","db_pass","db_name",
			"schema_path","auto_save");
	}

	public function setConfigs( $data ) {

		foreach($data as $key => $value) {

			if( !is_scalar($value) ) continue;
			if( !in_array($key,$this->expected) ) continue;

			$this->config[ $key ] = $value;

		}

	}

	public function getConfigs() {
		return $this->config;
	}

	public function getConfig( $key ) {
		return isset($this->config[$key]) ? $this->config[$key] : false;
	}

}

/* **********************************************************************************
 *
 *	PHPersist_Schema
 *
 *	XML Schema singleton for storing model to code relationship definitions.	
 *
 * *********************************************************************************/
class PHPersist_Schema {

	/* ******************************************************************************
	 *
	 *  Factory Pattern pieces
	 *  - public static instance of the configuration class
	 *  - public static factory method for return singleton instance
	 *
	 * *****************************************************************************/
	public static $instance = null;

	public static function getInstance() {

		if(self::$instance == null) {
			self::$instance = new PHPersist_Schema();
		}
		return self::$instance;
	
	}

	/* ******************************************************************************
	 *	
	 *	Internal mechanism for parsing and processing PHPersist Schema (XML) 
	 *	document.
	 *
	 * *****************************************************************************/


}

/* **********************************************************************************
 *
 *	PHPersist_DB
 *
 *	Database connection singleton used to generically 	
 *
 * *********************************************************************************/
class PHPersist_DB {

}


/* **********************************************************************************
 *
 *	(abstract) PHPersist_Model
 *
 *	Base class for PHPersist empowered objects.	
 *
 * *********************************************************************************/
abstract class PHPersist_Model {

	private $configuration	= null;
	private $schema			= null;
	private $db				= null;
	private $class			= "";

	/* ******************************************************************************
	 *
	 *	Constructor that:
	 *	- grabs Configuration singleton
	 *	- grabs XML Schema singleton (parses if needed)
	 *	- stores child class
	 *
	 * *****************************************************************************/
	public function __construct($class) {
		$this->configuration	= PHPersist_Config::getInstance();
		$this->schema			= PHPersist_Schema::getInstance();
		$this->class			= $class;
	}

	public static function getByPK($id) {

	}

	public static function getByFields($fields) {

	}

	public static function getByWhere($where) {

	}

}


















/* **********************************************************************************
 *
 *	TEST BATCH
 *	
 *	@TODO : Move this into seperate file
 *
 * *********************************************************************************/
if( true ) {


}

?>
