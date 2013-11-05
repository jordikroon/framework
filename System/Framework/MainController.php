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

class Maincontroller extends Application {

	protected $database;
	protected $twig;

	public function __construct() {
		$response = new Response;
		$route = $this -> getRouteMatches($response->getUri());
		
		$this -> loadTemplates();

	}

	/** Load template parser 'twig' 
	 * 
	 * @example http://twig.sensiolabs.org/documentation 
	 */
	protected function loadTemplates() {
		
		$parser = new Templating;
		$parser->setCacheDir(__dir__ . '/../../Application/Cache/twig');
		$parser->setViewDir(__dir__ . '/../../Application/View/');
		$this -> twig = $parser->getParser();
	}

	/** get route matches by request
	 * 
	 * @return array|boolean $date route data 
	 */
	public function getRouteMatches($request) {

		$router = $this -> getRouter();
		$route = $router -> getRoute($request);

		if ($route) {
			return $route -> getData();
		}

		return false;
	}


	public function execute() {
		
		$response = new Response;
		
		$route = $this -> getRouteMatches($response->getUri());

		if ($route) {

			$controllerClass = '\\Application\\Controller\\' . $route[0] . '\\' . $route[1] . 'Controller';

			$controller = new $controllerClass;
			$this -> response = $controller -> $route[2]();

		} else {
			throw new \ErrorException('Page not found');
			$this -> response = '@todo not found!';
		}

		return $this -> response;
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
			$route->handle($data[0], $data[1], $data[2]);
			
			$router -> setRoute($route);
		}

		return $router;
	}

}
