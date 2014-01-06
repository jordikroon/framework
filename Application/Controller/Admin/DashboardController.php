<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS\Admin
 */

namespace Application\Controller\Admin;

use Application\System\Controller\SecurityController;
use Application\Model\Auth;

class DashboardController extends SecurityController {

	public function index() {

		return $this -> twig -> render('Admin/users.html.twig');
	}
	
}
		