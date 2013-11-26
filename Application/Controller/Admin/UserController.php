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
use Application\Model\User;
use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;

class UserController extends MainController {

	private $users;

	public function __constuct() {
		parent::__construct();

		$this -> users = new User;
	}

	public function index() {

		$user = new User;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();
		
		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('user', 'pass', 'email', 'role'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('user', 'pass', 'email'));
			$validator -> rule('email', 'email');
			$validator -> rule('in', 'role', array(0, 1));

			if ($user -> exists(array('username' => $fields['user']))) {
				$error['user'][] = 'Username in use!';
			}

			if ($user -> exists(array('email' => $fields['email']))) {
				$error['email'][] = 'Email in use!';
			}

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {
				$user -> setUsername($fields['user']);
				$user -> setPassword($fields['pass']);
				$user -> setEmail($fields['email']);
				$user -> setRole($fields['role']);

				$user -> create();
				
				$confirmation['message'] = 'User has been created!';
			}
		}

		return $this -> twig -> render('Admin/users.html.twig', array('users' => $user -> getUsers(), 'field_errors' => $error, 'confirmation' => $confirmation));
	}

	public function update() {

		$user = new User;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();
		
		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('user', 'pass', 'email', 'role'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('user', 'pass', 'email'));
			$validator -> rule('email', 'email');
			$validator -> rule('in', 'role', array(0, 1));

			if ($user -> exists(array('username' => $fields['user']))) {
				$error['user'][] = 'Username in use!';
			}

			if ($user -> exists(array('email' => $fields['email']))) {
				$error['email'][] = 'Email in use!';
			}

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {
				$user -> setUsername($fields['user']);
				$user -> setPassword($fields['pass']);
				$user -> setEmail($fields['email']);
				$user -> setRole($fields['role']);

				$user -> create();
				
				$confirmation['message'] = 'User has been created!';
			}
		}

		return $this -> twig -> render('Admin/users.html.twig', array('users' => $user -> getUsers(), 'field_errors' => $error, 'confirmation' => $confirmation));
	}
	
	public function edit($id) {
		$user = new User;
		$user -> read($id);
		$user -> update();
		
		//return $this -> index();
	
		return $this -> twig -> render(
									'Admin/users.html.twig', array(
										'users' => $user -> getUsers(), 
										'field_errors' => array(), 
										'confirmation' => array(), 
										'edituser' => 'Edit', 
										'updateuser' => array(
											'id' => $user -> getId(),
											'user' => $user -> getUsername(),
											'email' => $user -> getEmail(),
										)
									));
	
	
	}
}
