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
		return $config -> get('BasePath');
	}

}
