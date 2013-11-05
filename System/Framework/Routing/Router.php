<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */
 
namespace System\Framework\Routing;

Class Router {

	private $routes = array();

	/** adds a route
	 * 
	 * @param object $route route object
	 */
	public function setRoute($route) {
		$this -> routes[] = $route;
	}

	/** returns the matched route
	 * 
	 * @param string $request request URI
	 *
	 * @return object $route matched route
	 */	
	public function getRoute($request) {
		foreach ($this->routes as $route) {
			if ($route -> match($request)) {
				return $route;
			}

		}
	}

}
