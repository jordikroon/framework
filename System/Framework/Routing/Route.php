<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */
 
namespace System\Framework\Routing;

Class Route {

	private $route;
	private $controller;
	private $data;
	private $name;
	private $param;
	
	/** initializes object and parses the data
	 * 
	 * @param string $route route
	 * @param string $data data in format Group_Controller:method
	 */
	public function handle($name, $route, $data) {

		$this -> route = $route;
		$this -> data = $this -> parseRouteData($data);
		$this -> name = $name;
	}
	
	public function parseRouteData($data) {
		$group = explode('_', $data);
		$method = explode(':', $group[1]);
		
		if(empty($group[0]) || empty($method[0]) || empty($method[1])) {
			throw new \InvalidArgumentException('Invalid route data string: ' . $data);
		} else {
			return array($group[0], $method[0], $method[1]);
		}
	}

	/** Returns route information
	 * 
	 * @return array $data route data
	 */
	public function getData() {
		return $this -> data;
	}

	/** Returns route name
	 * 
	 * @return array $name route name
	 */
	public function getName() {
		return $this -> name;
	}
	
	/** mathes the query with the route array
	 * 
	 * @param string $query request URI
	 * 
	 * @return array $route matched route
	 */
	public function match($query) {
			
		$route = '#^' . $this -> route . '$#';
		$route = preg_replace('/\<\:(.*?)\>/', '(?P<\1>[0-9]+)', $route); // <:id>
		$route = preg_replace('/\<\#(.*?)\>/', '(?P<\1>[A-Za-z0-9\-\_]+)', $route); // <#string>

		if (preg_match($route, $query, $matches)) {
			return $matches;
		}
	}

	public function getByName($name) {
		require __dir__ . '/../../../Config/routes.php';
		
		foreach($routes AS $route) {
			if($route[0] == $name) {
				return $route;
			}
		}
	}
	
	
	public function setParam($param) {
		$this -> param = $param;
	}
	
	public function getParam() {
		return $this -> param;
	}
}
