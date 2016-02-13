<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */
 
namespace Application\Controller\Contact;

use System\Framework\MainController;
use Application\Model\Settings;
use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;
use System\Framework\Mailer;
use System\Framework\Security;

Class ContactController extends MainController {

	public function index() {
		$content = array();
		
		$form = new FormHandler;
		
		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('email', 'message', 'name', '_token'));
			
			$validator = new FormValidator($fields);
			$validator -> rule('email', 'email');
			
			$security = new Security;
			if(!$security -> checkSignature($fields['_token'])) {
				$content['error'] = 'The provided token has been expired or is invalid!';
			} else if (!$validator -> validate()) {
				$content['error'] = 'Your message could not been sent, please fill in a valid e-mail-adress!';
			} else {
				$validator -> rule('required', array('email', 'message', 'name'));
				if (!$validator -> validate()) {
					$content['error'] = 'Your message could not been sent, please fill in all the fields!';
				} else {
					
					try {
						
					$settings = new Settings;
					$site = $settings -> read(1);

					$mailer = new Mailer;
					
					$message = \Swift_Message::newInstance(sprintf('New contact message at %s', $site -> getSiteTitle()))
					  ->setFrom(array($site -> getAdminEmail() => $site -> getAdminName()))
					  ->setTo(array($site -> getAdminEmail() => $site -> getAdminName()))
					  ->setBody(sprintf("From: %s\nEmail: %s\n\nMessage:\n%s", $fields['name'], $fields['email'], $fields['message']));
  					

						$mailer -> send($message);
						$content['succes'] = 'Your message been sent, you will be contacted shortly!';
					} 
					catch(\Exception $e){
						$content['error'] = 'Your message could not been sent, an unknown error occurred!';
						
					}
				}
			}
		}
		
		return $this -> twig -> render('Contact/contact.html.twig', array('content' => $content));
	}

}
