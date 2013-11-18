<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS\Admin
 */

namespace Application\Controller\Admin;

use System\Framework\MainController;
use Application\Model\Auth;

class DashboardController extends MainController {

	public function index() {

		return $this -> twig -> render('Admin/users.html.twig');
	}
	
}
		