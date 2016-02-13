<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */

namespace System\Framework;

use System\Framework\MainController;
use System\Framework\Exception\ExceptionHandler;
use System\Framework\HTTP\Response;

class Application {

	/** core of the application
	 *
	 * @return string $execute output of pages
	 */
	final public static function run() {
		try {
			$maincontroller = new MainController;

			return $maincontroller -> execute();
		} catch(\Exception $e) {

			$exception = new ExceptionHandler($e);
			$exception -> save(__dir__ . '/../Logs/Exceptions.log');
			
			
			if(debug_mode == 1) {
				return $exception -> getContent();
			} else {
				$response = new Response;
				$response -> redirect(500);
			}
		}
	}

}
