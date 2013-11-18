<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */

namespace Application\Controller\Blog;

use System\Framework\MainController;

class BlogController extends MainController {

	public function index() {
		
		return $this -> twig -> render('Menu/menu.html.twig', array('menu' => $menu -> getItems()));
	}
	
}
		