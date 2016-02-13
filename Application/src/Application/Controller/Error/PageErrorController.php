<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */

namespace Application\Controller\Error;

use System\Framework\MainController;
use Application\Model\Settings;
use System\Framework\HTTP\Response;
use System\Framework\Config;

Class PageErrorController extends MainController {

	public function notFound() {
		
		$response = new Response;
		$response -> returnStatusCode('404');
		
		return $this -> twig -> render('ErrorDocs/404.html.twig');
	}

}
