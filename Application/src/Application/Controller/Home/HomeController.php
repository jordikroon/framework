<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */
 
namespace Application\Controller\Home;

use System\Framework\MainController;
use KzykHys\Pygments\Pygments;
use System\Framework\HTTP\Response;

Class HomeController extends MainController {

	public function index() {		
		return $this -> twig -> render('Home/index.html.twig', array('content' => ''));
	}
	
	public function loadPygStyle($style) {
		header("Content-type: text/css");
		header("X-Content-Type-Options: nosniff");
		
		try {
			$pygments = new Pygments();
			return $pygments->getCss($style);
		}
		
		catch(\Exception $e) {
			return;
		}	
	}
}
