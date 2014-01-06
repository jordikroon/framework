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

use Application\Model\Auth;
use Application\Model\User;

Class LoginController extends MainController {

	public function index($type) {

		$render = array();

		$auth = new Auth;
		$session = new Session;

		$response = new Response();

		if (!$auth -> isAuthenticated()) {

			$form = new FormHandler;

			if ($form -> isMethod('post')) {

				$user = new User;
				$user -> setUsername($form -> getValue('username'));
				$user -> setPassword($form -> getValue('password'));

				if ($auth -> check($user)) {
					$session -> create('key', $auth -> getKey());
					$response -> redirect('home');
				} else {
					$render['loginfalse'] = true;
				}
			}
		} else {
			$session -> get('key');
			//todo... db check:)

			$response -> redirect('home');
		}

		if ($type == 'admin') {
			return $this -> twig -> render('Admin/login.html.twig', $render);
		} else {
			return $this -> twig -> render('Login/login.html.twig', $render);
		}
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

}
