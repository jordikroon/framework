<?php

namespace System\Framework\Template\Extension;

use System\Framework\HTTP\Response;
use System\Framework\Routing\Route;

class CoreExtension extends \Twig_Extension {

    public function getFilters()
    {
        return array(
            
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('url', function($routeName) {
				$response = new Response;
            	return $response -> url($routeName);
            }),
            new \Twig_SimpleFunction('render', function($controllerName, $args = array()) {
				$route = new Route;
            	$data = $route -> parseRouteData($controllerName);
				
			
				$controllerClass = '\\Application\\Controller\\' . $data[0] . '\\' . $data[1] . 'Controller';

				if(class_exists($controllerClass)) {
					$controller = new $controllerClass;
					return call_user_func_array(array($controller, $data[2]), $args);
				} else {
					throw new \ErrorException('Could not find controller or method not found: ' . $controllerClass);
				}
            }, array('is_safe' => array('html')))
        );
    }
    public function getName()
    {
        return 'project';
    }
}
