<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */
 
namespace System\Framework\Form;

class FormHandler {
	
	/** gets the post variable from the post superglobal
	 * 
	 * @return string $_POST post variable
	 */
	public function getValue($name) {
		if(isset($_POST[$name]) && !empty($_POST[$name])) {
			return $_POST[$name];
		} else if(isset($_FILES[$name]) && !empty($_FILES[$name])) {
			return $_FILES[$name];
		} 
	}
	
	/** 
	 * manually set post variable
	 * 
	 * @param string $name POST name
	 * @param string $value POST value
	 */
	public function setValue($name, $value) {
		$_POST[$name] = $value;
	}

	/** 
	 * manually unset post variable
	 */	
	public function unsetValue($name) {
		unset($_POST[$name]);
	}
	
	/** 
	 * unset all post variables
	 */	
	public function unsetAll() {
		foreach($_POST as $key => $value) {
			unset($_POST[$key]);
		}
	}

	/**
	 * unset all post variables
	 *
	 * @param array $array selected form field values
	 * @return array
	 */
	public function getFields($array) {
		
		if(!is_array($array)) {
			throw new \InvalidArgumentException('Parameter should be an array! Got: ' . gettype($array));
		} else {
			
			$post = array();
			foreach($array AS $name) {
				$value = $this -> getValue($name);
				$post[$name] = ($value == false ? '' : $value);
				
			}
			
			return $post;
		}
		
	}

	/**
	 * Check method is equal
	 *
	 * @param string $method form method to check
	 * @return bool
	 */
	public function isMethod($method) {
		if($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
			return true;
		}
	}
}
