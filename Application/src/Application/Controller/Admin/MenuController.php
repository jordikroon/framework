<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS\Admin
 */

namespace Application\Controller\Admin;


use System\Framework\MainController;

use Application\Model\Menu;
use Application\Model\MenuItem;
use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;
use System\Framework\Security;

class MenuController extends MainController {

	public function index() {

		$menu = new menu('head');
		$menuItem = new MenuItem;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();

		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('name', 'link', '_token'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('name', 'link'));

			if ($menu -> itemExists(array('link' => $fields['link']))) {
				$error['link'][] = 'Link already exist';
			}
			
			$security = new Security;
			if(!$security -> checkSignature($fields['_token'])) {
				$error['csrf'][] = 'Invalid token provided!';
			}

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {

				$menuItem -> setName($fields['name']);
				$menuItem -> setLink($fields['link']);

				$menu -> addItem($menuItem);

				$confirmation['message'] = 'Item has been created!';

				$form -> unsetAll();
			}
		}
	
		return $this -> twig -> render('Admin/menu.html.twig', array('menuitems' => $menu -> getItems(), 'field_errors' => $error, 'confirmation' => $confirmation));
	}

	public function edit($id) {

		$menu = new menu('head');
		$menuItem = new MenuItem;
		$form = new FormHandler;
		
		$error = array();
		$confirmation = array();

		$menuItem -> setId($id);
		$item = $menuItem -> readItem($id);
		
		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('name', 'link', '_token'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('name', 'link'));

			$security = new Security;
			if(!$security -> checkSignature($fields['_token'])) {
				$error['csrf'][] = 'Invalid token provided!';
			} 
			
			
			if ($menu -> itemExists(array('link' => $fields['link']))) {
				$error['link'][] = 'Link already exist';
			}

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}
			
			if (empty($error)) {

				$menuItem -> setLink($fields['link']);
				$menuItem -> setName($fields['name']);
				$menu -> updateItem($menuItem);

				$confirmation['message'] = 'menu item has been edited!';
			}
		}
		
		
		$form -> setValue('link', $item -> getLink());
		$form -> setValue('name', $item -> getName());
				
		return $this -> twig -> render('Admin/menu.html.twig', array('menuitems' => $menu -> getItems(),'field_errors' => $error, 'confirmation' => $confirmation, 'editmenu' => 'Edit', 'updatemenu' => array('id' => $menuItem -> getId())));

	}

	public function delete($id) {

		$menu = new menu('head');
		$menuItem = new menuItem;
		$menuItem -> setId($id);
		$menu -> removeItem($menuItem);

		$confirmation = array();
		$confirmation['message'] = 'Item has been deleted!';

		return $this -> twig -> render('Admin/menu.html.twig', array('menuitems' => $menu -> getItems(), 'field_errors' => array(), 'confirmation' => $confirmation));
	}

}
