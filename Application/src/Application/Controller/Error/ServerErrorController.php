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

use System\Framework\Config;
use System\Framework\HTTP\Response;

Class ServerErrorController extends MainController {

	public function serverError() {
	
		$response = new Response;
		$response -> returnStatusCode('500');
		
		return $this -> twig -> render('ErrorDocs/500.html.twig');
	}

}
