<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */

namespace System\Framework\Template;

class TwigLoader extends \Twig_Environment{
	
	private $name;
	
	public function __construct($loader, $options = array()) {
		parent::__construct($loader, $options);
	}
	
	public function render($name, array $context = array()) {
		return parent::render($name, $context);
		
	}	
	
	public function getName() {
		return $this -> name;
	}
}
