<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */
 
namespace System\Framework\Exception;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use System\Framework\Template\Templating;

class ExceptionHandler {

	private $exception;

	public function __construct($e) {
		$this -> exception = $e;
	}

	public function save($file) {
		$log = new Logger(get_class($this -> exception));
		$log -> pushHandler(new StreamHandler($file, Logger::DEBUG));

		$log -> addCritical(sprintf("Exception thrown: %s", $this -> exception -> __toString()));
	}

	public function getContent() {

		$template = new Templating;
		$template -> setCacheDir(__dir__ . '/../../../Application/Cache/twig');
		$template -> setViewDir(__dir__ . '/../../Views/');

		return $template -> getParser() -> render('exception.html.twig', array('name' => get_class($this -> exception), 'message' => $this -> exception -> getMessage(), 'stack' => $this -> exception -> getTraceAsString()));
		
	}

}
