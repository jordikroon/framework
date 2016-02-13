<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */

namespace System\Framework;

use System\Framework\Routing\Router;
use System\Framework\Routing\Route;
use System\Framework\HTTP\Response;
use System\Framework\Template\Templating;
use System\Framework\Security;
use System\Framework\Config;

class Maincontroller extends Application {

	protected $database;
	protected $twig;
	private $params;

	public function __construct() {
		$this -> config = new config;
		$this -> loadTemplates();
	}

	/** Load template parser 'twig'
	 *
	 * @example http://twig.sensiolabs.org/documentation
	 */
	protected function loadTemplates() {

		$parser = new Templating;
		$parser -> setCacheDir(__dir__ . '/../../Application/Cache/twig');
		$parser -> setViewDir(__dir__ . '/../../Application/View/');
		$this -> twig = $parser -> getParser();
		
	}

	/** get route matches by request
	 *
	 * @return array|boolean $date route data
	 */
	public function getRouteMatches($request) {
		$request = ltrim($request, '/');

		$router = $this -> getRouter();
		$route = $router -> getRoute($request);

		if ($route) {

			$this -> params = $route -> getParams();

			return $route -> getData();
		}

		return false;
	}

	public function execute() {

		$response = new Response;

		$route = $this -> getRouteMatches($response -> getUri());
		$security = new Security;
		
		$authorized = $security -> isAuthorized($route[0] . '_' . $route[1] . ':' . $route[2]);
		if ($authorized == 2) {

			if ($route) {
				$controllerClass = '\\Application\\Controller\\' . $route[0] . '\\' . $route[1] . 'Controller';

				$controller = new $controllerClass;
				$reflection = new \ReflectionMethod($controller, $route[2]);

				$array = array_slice($this -> params, 0, count($reflection -> getParameters()));
				$this -> response = call_user_func_array(array($controller, $route[2]), $array);
				
			} else {
				throw new \ErrorException('Page not found');
				$response -> redirect($securityarray[404]);
			}

			return $this -> response;
		} else {
			$response = new Response;
			$securityconf = new Config;
			$securityconf -> loadFile(__dir__ . '/../../Config/security.php');

			$securityarray = $securityconf -> get('security');
			if ($authorized == 1) {
				$response -> redirect($securityarray['notauthroute']);
			} else {
				$response -> redirect($securityarray['loginroute']);
			}	
		}
	}

	/** gets and returns route object
	 *
	 * @return object $route route object
	 */
	public function getRouter() {

		$router = new Router;

		require __dir__ . '/../../Config/routes.php';

		foreach ($routes AS $data) {
			$route = new Route;
			$route -> handle($data[0], $data[1], $data[2]);

			$router -> setRoute($route);
		}

		return $router;
	}

}