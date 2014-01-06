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
use Application\Model\User;
use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;

class UserController extends SecurityController {

	public function index() {

		$user = new User;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();

		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('user', 'pass', 'email', 'role', 'fullname'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('user', 'pass', 'email', 'fullname'));
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
				$user -> setFullname($fields['fullname']);
				
				$user -> create();
				
				$form -> unsetAll();
				$confirmation['message'] = 'User has been created!';
			}
		}

		return $this -> twig -> render('Admin/users.html.twig', array('users' => $user -> getUsers(), 'field_errors' => $error, 'confirmation' => $confirmation));
	}

	public function edit($id) {
		$user = new User;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();
		
		$user -> read($id);

		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('user', 'pass', 'email', 'role', 'fullname'));

			$validator = new FormValidator($fields);

			$required = array();
			$required[] = 'fullname';
			
			if ($user -> getUsername() != $fields['user']) {
				$required[] = 'user';

				if ($user -> exists(array('username' => $fields['user']))) {
					$error['user'][] = 'Username in use!';
				}
			}

			if ($fields['pass'] != '') {
				$required[] = 'pass';
			}

			if ($user -> getEmail() != $fields['email']) {
				$required[] = 'email';
				if ($user -> exists(array('email' => $fields['email']))) {
					$error['email'][] = 'Email in use!';
				}
			}

			$validator -> rule('required', $required);
			$validator -> rule('email', 'email');
			$validator -> rule('in', 'role', array(0, 1));

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {

				$user -> setUsername($fields['user']);

				if ($fields['pass'] != '') {
					$user -> setPassword($fields['pass']);
				} else {
					$user -> setPassword($user -> getPassword());
				}
				$user -> setFullname($fields['fullname']);
				$user -> setEmail($fields['email']);
				$user -> setRole($fields['role']);

				$user -> update();

				$confirmation['message'] = 'User has been edited!';
			}
		}
		//return $this -> index();

		return $this -> twig -> render('Admin/users.html.twig', array('users' => $user -> getUsers(), 'field_errors' => $error, 'confirmation' => $confirmation, 'edituser' => 'Edit', 'updateuser' => array('id' => $user -> getId(), 'user' => $user -> getUsername(), 'fullname' => $user -> getFullname(), 'email' => $user -> getEmail(), )));

	}
	
	public function delete($id) {
		
		$user = new User;
		
		$user -> read($id);
		$user -> delete();
		
		$confirmation = array();
		$confirmation['message'] = 'User has been deleted!';
		
		return $this -> twig -> render('Admin/users.html.twig', array('users' => $user -> getUsers(), 'field_errors' => array(), 'confirmation' => $confirmation));
	}
}
