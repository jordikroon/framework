<?php

namespace System\Framework;

use System\Framework\Routing\Route;
use System\Framework\HTTP\Response;
use Kunststube\CSRFP\SignatureGenerator;
use System\Framework\Storage\Session;

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

					if (count($security['roles']) !== count(array_unique($security['roles']))) {
						throw new \LogicException('We detected duplicated role values in your security config file, please fix this issue!');
					} else {			
						$response = $controller -> $method();
						
						if (!in_array(array_search($response[0], $security['roles']), $roles)) {
							if($response[1] == true) {
								return 1; // logged in but not authorized
							} else {
								return 0; // not logged in
							}
							
						}
					}
				} else {
					throw new \ErrorException(sprintf('LoginRoute "%s" does not exists', $security['checklogin']));
				}

			}
		}
		return 2; // all oke
	}

	public function checkSignature($token) {
		$security = $this -> config -> get('security');
		
		$session = new Session;
		$signer = new SignatureGenerator($session -> get('csrfhash'));

		return ($signer -> validateSignature($token) ? true : false);
	}

	public function generateSignature() {

		$security = $this -> config -> get('security');
		
		$session = new Session;
		$session -> create('csrfhash', $security['csrfsecret'] . uniqid(), true);
		$signer = new SignatureGenerator($session -> get('csrfhash'));

		return htmlspecialchars($signer -> getSignature());
	}

}
