<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */

namespace System\Framework\Storage;

class Session {

	/**
	 * Starts the session when it is not already started
	 */
	public function __construct() {
		if(session_id() == '') {
			session_start();
		}
	}
	
	/** Creates a new session variable
	 * 
	 * @param string $sessionName session name
	 * @param string $value session value
	 */
	public function create($sessionName, $value, $overwrite = false) {
		
		if(!$this -> exists($sessionName) || $overwrite == true) {
			$_SESSION[$sessionName] = $value;
		}
	}

	/** Deletes session variable by name
	 * 
	 * @param string $sessionName session name
	 */
	public function delete($sessionName) {
		unset($_SESSION[$sessionName]);
	}

	/** Gets session variable by name
	 * 
	 * @param string $sessionName session name
	 * 
	 * @return mixed $_SESSION session value
	 */	
	public function get($sessionName) {
		return $_SESSION[$sessionName];
	}
	
	/** Updates a session variable
	 * 
	 * @param string $sessionName session name
	 * @param string $value session value
	 */
	public function update($sessionName, $value) {
		if($this -> exists($sessionName)) {
			$_SESSION[$sessionName] = $value;
		}	
	}

	/** Check session variable exists
	 * 
	 * @param string $sessionName session name
	 * 
	 * @return boolean
	 */	
	public function exists($sessionName) {
		if(isset($_SESSION[$sessionName]) && !empty($_SESSION[$sessionName])) {
			return true;
		} 
	}
	
	/** 
	 * Removes all session variables
	 */	
	public function deleteAll() {
		session_destroy();
	}

}
