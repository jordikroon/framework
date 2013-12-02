<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */

namespace Application\Controller\Blog;

use System\Framework\MainController;
use Application\Model\Blog;

class BlogController extends MainController {

	public function index() {
		
		$blog = new Blog;
		
		return $this -> twig -> render('Blog/blog.html.twig', array('blogitems' => $blog -> getItems()));
	}
	
}
		