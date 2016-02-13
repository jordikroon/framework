<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS\Admin
 */

namespace Application\Controller\Admin;

use System\Framework\Maincontroller;
use Application\Model\Settings;
use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;
use System\Framework\Security;

class DashboardController extends Maincontroller {

	public function index() {
		$settings = new Settings;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();

		$settings -> read(1);
	
		if ($form -> isMethod('post')) {
			
			$fields = $form -> getFields(array('adminname', 'adminemail', 'title', 'descr', 'stitle', 'sdescr', 'surl', '_token'));

			$validator = new FormValidator($fields);
			$validator -> rule('required', array('adminname', 'adminemail', 'title', 'descr', 'stitle', 'sdescr', 'surl'));
			$validator -> rule('email', 'adminemail');

			$security = new Security;
			if(!$security -> checkSignature($fields['_token'])) {
				$error['csrf'][] = 'Invalid token provided!';
			}
			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {

				 $settings -> setAdminName($fields['adminname']);
				 $settings -> setAdminEmail($fields['adminemail']);
				 $settings -> setSiteDescription($fields['descr']);
				 $settings -> setSiteTitle($fields['title']);
				 $settings -> setSpotlightTitle($fields['stitle']);
				 $settings -> setSpotlightDescription($fields['sdescr']);
				 $settings -> setSpotlightUrl($fields['surl']);
				 
				 $settings -> update();
				$confirmation['message'] = 'Settings have been updated!';
			}
		}
		//return $this -> index();

		return $this -> twig -> render('Admin/dashboard.html.twig', array('field_errors' => $error, 'confirmation' => $confirmation, 'updatesettings' => array('adminname' => $settings -> getAdminName(), 'adminemail' => $settings -> getAdminEmail(), 'title' => $settings -> getSiteTitle(), 'descr' => $settings -> getSiteDescription(), 'stitle' => $settings -> getSpotlightTitle(), 'sdescr' => $settings -> getSpotlightDescription(), 'surl' => $settings -> getSpotlightUrl())));

	}

}
