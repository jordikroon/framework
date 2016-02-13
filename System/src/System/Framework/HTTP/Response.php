<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */
 
namespace System\Framework\HTTP;

use System\Framework\Routing\Route;
use System\Framework\Config;

class Response {
	
	private static $statusCodes = array (
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended'
        );
		
	private $basePath;
	
	public function redirect($routeName, $status_code = 302) {

		$route = new Route;
		$routeInfo = $route -> getByName($routeName);

		if (!$routeInfo) {
			throw new \Exception(sprintf('Route %s not found!', $routeName));
		} else {
			if (!headers_sent()) {
				header('location: ' . $this -> getBasePath() . $routeInfo[1], false, $status_code);
			} else {
				throw new \ErrorException('Headers already send, could not redirect.');
			}
		}

		exit();
	}

	protected function URLRedirect($url, $status_code = 302) {

		if (!headers_sent()) {
			header('location: ' . $url, true, $status_code);
		} else {
			throw new \ErrorException('Headers already send, could not redirect.');
		}

		exit();
	}

	protected function refresh() {
		if (!headers_sent()) {
			header('location: ' . $this -> getBasePath() . $this -> getUri());
		} else {
			throw new \ErrorException('Headers already send, could not refresh page.');
		}

		exit();
	}

	public function url() {

		$params = func_get_args();
	
		$route = new Route;
		$routeInfo = $route -> getByName($params[0]);

		unset($params[0]);
		if (!$routeInfo) {
			throw new \Exception(sprintf('Route %s not found!', $routeInfo[1]));
		} else {
			
			$url = $routeInfo[1];
			foreach($params AS $param) {
				
				$url = preg_replace('/<[:|#](.+?)>/', $param, $url, 1);
						
			}
		
			
			return $this -> getBasePath() . $url;
		}
	
	}
	
	public function getUri() {
		if (isset($_GET['r'])) {
			$uri = $_GET['r'];
		} else {
			$uri = '';
		}

		return $uri;
	}

	public function setBasePatch($basePath) {
		$this -> basePath = $basePath;
	}
	
	public function getBasePath() {
		$config = new config;
		$config -> loadFile(__dir__ . '/../../../../../Config/application.php');
		
		return $config -> get('BasePath');
	}

	public function returnStatusCode($statusCode) {
		if (self::$statusCodes[$statusCode] !== null) {
			header(sprintf('%s %d %s', $_SERVER['SERVER_PROTOCOL'], $statusCode, self::$statusCodes[$statusCode]), true, $statusCode);
		} else {
			throw new ErrorException(sprintf('Invalid status code %d', $statusCode));
		}
	}
}
