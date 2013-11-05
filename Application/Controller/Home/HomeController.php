<?php

namespace Application\Controller\Home;

use System\Framework\MainController;

Class HomeController extends MainController {

	public function index() {

		return $this -> twig -> render('Home/index.html.twig', array('name' => 'Jordi'));
	}

}
