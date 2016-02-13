<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */

namespace Application\Controller\Auth;

use System\Framework\MainController;
use System\Framework\Storage\Session;
use System\Framework\Form\FormHandler;
use System\Framework\HTTP\Response;
use System\Framework\Security;

use Application\Model\Auth;
use Application\Model\User;

Class LoginController extends MainController {

	public function index() {

		$render = array();

		$auth = new Auth;
		$session = new Session;

		$response = new Response();

		if (!$auth -> isAuthenticated()) {

			$form = new FormHandler;

			$security = new Security;

			if ($form -> isMethod('post')) {
				if (!$security -> checkSignature($form -> getValue('_token'))) {
					$render['csrferror'] = 'Invalid token provided!';
				} else {
					$user = new User;
					$user -> setUsername($form -> getValue('username'));
					$user -> setPassword($form -> getValue('password'));

					if ($uid = $auth -> check($user)) {
						$session -> create('key', $auth -> getKey());
						$session -> create('uid', (int)$uid);

						$response -> redirect('admin');
					} else {
						$render['loginfalse'] = true;
					}
				}
			}
		} else {
			$session -> get('key');
			//todo... db check:)
			
			$response -> redirect('admin');
		}

		return $this -> twig -> render('Admin/login.html.twig', $render);

	}

	public function logOut() {
		$session = new Session;
		$session -> delete('key');

		$response = new Response();
		$response -> redirect('login');
	}

	public function loginUser() {
		return $this -> index('user');
	}

	public function checkLogin() {

		$auth = new Auth;
		$session = new Session;
		if ($auth -> isAuthenticated()) {
			$user = new User;
			$user -> read($session -> get('uid'));

			return array($user -> getRole(), true);
		}
		return array(0, false);
	}

}
