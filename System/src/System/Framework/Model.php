<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */
 
namespace System\Framework;

use System\Framework\Database\Database;

class Model {

	/** gets and returns database object
	 * 
	 * @return object Database database object
	 */
	public function getDatabase() {

		if (class_exists(__NAMESPACE__ . '\Database\Database')) {
			return new Database;
		} else {
			throw new \PDOException('Database class not found');
		}
	}
}
