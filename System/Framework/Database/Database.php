<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */
 
namespace System\Framework\Database;

use System\Framework\Config;

class Database extends \PDO {

	public function __construct() {

		$config = new Config;
		echo __dir__;
		$config -> loadFile(__dir__ . '/../../../Config/application.php');
		$db = $config -> get('database');

		parent::__construct('mysql:dbname=' . $db['database'] . ';host=' . $db['host'], $db['username'], $db['password']);
		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

}
