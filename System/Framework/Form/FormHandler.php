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
		}
	}
	
	/** 
	 * manually set post variable
	 */
	public function setValue($name, $value) {
		$_POST[$name] = $value;
	}
	
	public function unsetValue($name) {
		unset($_POST[$name]);
	}
	
	public function unsetAll() {
		foreach($_POST as $key => $value) {
			unset($_POST[$key]);
		}
	}
	
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
	
	public function isMethod($method) {
		if($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
			return true;
		}
	}
}
