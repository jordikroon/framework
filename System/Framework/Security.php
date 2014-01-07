<?php

namespace System\Framework;

use System\Framework\Routing\Route;
use System\Framework\HTTP\Response;

class Security {

	private $config;

	public function __construct() {
		$this -> config = new Config;
		$this -> config -> loadFile(__dir__ . '/../../Config/security.php');
	}

	public function isAuthorized($routeName) {
		$route = new Route;

		$security = $this -> config -> get('security');

		foreach ($security['securedroutes'] AS $key => $roles) {
			$routeInfo = $route -> getByName($key);

			if ($routeInfo[2] == $routeName) {
				$data = $route -> parseRouteData($security['checklogin']);

				$controllerClass = '\\Application\\Controller\\' . $data[0] . '\\' . $data[1] . 'Controller';

				if (class_exists($controllerClass) && method_exists($controllerClass, $data[2])) {
					$controller = new $controllerClass;
					$method = $data[2];
					$return = $controller -> $method();
					if (!in_array(array_search($return, $security['roles']), $roles)) {
						return false;
					}

				} else {
					throw new \ErrorException(sprintf('LoginRoute "%s" does not exists', $security['checklogin']));
				}

			}
		}
		return true;
	}

}
