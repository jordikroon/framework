<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */
 
namespace Application\Controller\Home;

use System\Framework\MainController;
use Application\Model\Settings;

Class TopController extends MainController {

	public function index() {

		$settings = new Settings;
		
		$site = $settings -> read(1);
		
		$content = array();
		$content['sitetitle'] = $site -> getSiteTitle();
		$content['sitedescr'] = $site -> getSiteDescription();
		$content['spotlighttitle'] = $site -> getSpotlightTitle();
		$content['spotlightdescr'] = $site -> getSpotlightDescription();
		$content['spotlighturl'] = $site -> getSpotlightUrl();
		
		return $this -> twig -> render('Core/topBar.html.twig', array('content' => $content));
	}

}
