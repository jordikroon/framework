<?php

namespace Application\Controller\Auth;

use System\Framework\MainController;
use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;
use System\Framework\HTTP\Response;

use Application\Model\User;

class RegisterController extends MainController {

	public function index() {

		$form = new FormHandler;
		
		

		if ($form -> isMethod('post')) {
			
			
			$validator = new FormValidator($form->getFields(array('username', 'password', 'password2', 'email')));
			$user = new User;

			if ($user -> exists(array('username' => 'test', 'role' => 0))) {
				echo 'bestaat al';
			} else {
				$user -> setUsername('test');
				$user -> setPassword('test');
				$user -> setEmail('test@test.nl');

				$user -> create();

				echo 'toegevoegd';
			}
		}
		return $this -> twig -> render('Login/register.html.twig');

	}

}
