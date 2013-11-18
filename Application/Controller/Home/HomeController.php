<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */
 
namespace Application\Controller\Home;

use System\Framework\MainController;

Class HomeController extends MainController {

	public function index() {

		return $this -> twig -> render('Home/index.html.twig', array('name' => 'Jordi'));
	}

}
