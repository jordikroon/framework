<?php

namespace System\Framework;

use System\Framework\Exception\FileNotFoundException;

class Config {
	
	private $config;
	
	public function __construct($file = '') {
			
		if(!empty($file)) {
			$this -> loadFile($file);
		}
	}
	
	public function loadFile($file) {
		if(file_exists($file)) {

			$config = require $file;
			
			if(!isset($config) || !is_array($config)) {
				$this -> config = array();
				throw new \InvalidArgumentException('Config is not a valid array!');
			} else {
				
				$this -> config = $config;
			}

		} else {
			throw new FileNotFoundException($file);
		}
	}
	public function get($name) {
		return $this -> config[$name];
	}
	
}
