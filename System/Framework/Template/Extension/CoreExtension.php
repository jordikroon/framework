<?php

namespace System\Framework\Template\Extension;

use System\Framework\HTTP\Response;
use System\Framework\Routing\Route;
use System\Framework\Form\FormHandler;

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
				
				$routeName = str_replace(array('<:id>'), '', $routeName);
            	return $response -> url($routeName);
            }),
            new \Twig_SimpleFunction('base_path', function() {
				$response = new Response;
            	return $response -> getBasePath();
            }),
            new \Twig_SimpleFunction('formValue', function($name) {
				$form = new FormHandler;
				
            	return $form -> getValue($name);
            }),
            
            new \Twig_SimpleFunction('formSelectValue', function($name, $value) {
				$form = new FormHandler;
				
				if(is_array($form -> getValue($name))) {
            		return in_array($value, $form -> getValue($name)) ? 'selected="selected"' : '';
				} else {
					return ($form -> getValue($name) == $value) ? 'selected="selected"' : '';
				}
            }, array('is_safe' => array('html'))),
   
            new \Twig_SimpleFunction('formRadioValue', function($name, $value) {
				$form = new FormHandler;
				
				if(is_array($form -> getValue($name))) {
					return in_array($value, $form -> getValue($name)) ? 'checked' : '';
				} else {
            		return ($form -> getValue($name) == $value) ? 'checked' : '';
				}
				
            }, array('is_safe' => array('html'))),
            
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
