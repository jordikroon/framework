<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */
 
namespace Application\Controller\Auth;

use System\Framework\MainController;
use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;
use System\Framework\HTTP\Response;

use Application\Model\User;

class RegisterController extends MainController {

	public function index() {

		$form = new FormHandler;

		$error = array();

		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('username', 'password', 'password2', 'email'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('username', 'password', 'password2', 'email'));
			$validator -> rule('email', 'email');
			$validator -> rule('equals', 'password', 'password2');
			$validator -> rule('length', 'password', 7);

			$user = new User;

			if ($user -> exists(array('username' => $fields['username']))) {
				$error['username'][] = 'Username in use!';
			}

			if ($user -> exists(array('email' => $fields['email']))) {
				$error['email'][] = 'Email in use!';
			}

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {
				$user -> setUsername($fields['username']);
				$user -> setPassword($fields['password']);
				$user -> setEmail($fields['email']);

				$user -> create();

				$response = new Response();

				return $this -> twig -> render('Login/register-complete.html.twig', array('url' => $response -> url('home'), 'name' => $fields['username']));
			}
		}

		return $this -> twig -> render('Login/register.html.twig', array('error' => $error));
	}

}