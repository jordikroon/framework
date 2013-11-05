<?php

namespace System\Framework;

class Config {
	
	private $config;
	
	public function __construct() {

		require __dir__ . '/../../Config/application.php';		
		
		if(!isset($config) || !is_array($config)) {
			$this -> config = array();
			throw new \ErrorException('Config is not a valid array!');
		} else {
			$this -> config = $config;
		}
	}
	public function get($name) {
		return $this -> config[$name];
	}
	
}
