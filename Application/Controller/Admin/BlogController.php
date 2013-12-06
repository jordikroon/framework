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
use Application\Model\Blog;
use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;

class BlogController extends MainController {

	public function index() {

		$user = new User;
		$blog = new Blog;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();

		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('title', 'author', 'content', 'published'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('title', 'author', 'content'));
			$validator -> rule('in', 'published', array(0, 1));

			if (!$user -> exists(array('id' => $fields['author']))) {
				$error['user'][] = 'Author not found!';
			}

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {

				$blog -> setAuthor($fields['author']);
				$blog -> setTitle($fields['title']);
				$blog -> setContent($fields['content']);
				$blog -> setPublished($fields['published']);

				$blog -> create();

				$confirmation['message'] = 'Item has been created!';

				$form -> unsetAll();
			}
		}

		return $this -> twig -> render('Admin/blog.html.twig', array('blogitems' => $blog -> getItems(), 'authors' => $user -> getUsers(), 'field_errors' => $error, 'confirmation' => $confirmation));
	}

	public function edit($id) {

		$blog = new Blog;
		$user = new User;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();

		$blog -> read($id);

		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('title', 'author', 'content', 'published'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('title', 'author', 'content'));
			$validator -> rule('in', 'published', array(0, 1));

			if (!$user -> exists(array('id' => $fields['author']))) {
				$error['user'][] = 'Author not found!';
			}

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}
			if (empty($error)) {

				$blog -> setAuthor($fields['author']);
				$blog -> setTitle($fields['title']);
				$blog -> setContent($fields['content']);
				$blog -> setPublished($fields['published']);

				$blog -> update();

				$confirmation['message'] = 'Blog item has been edited!';
			}
		}

		return $this -> twig -> render('Admin/blog.html.twig', array('blogitems' => $blog -> getItems(), 'authors' => $user -> getUsers(), 'field_errors' => $error, 'confirmation' => $confirmation, 'editblog' => 'Edit', 'updateblog' => array('id' => $blog -> getId(), 'author' => $blog -> getAuthor(), 'title' => $blog -> getTitle(), 'content' => $blog -> getContent(), 'published' => $blog -> getPublished(), )));

	}

	public function delete($id) {

		$blog = new Blog;
		$user = new User;
		$blog -> read($id);
		$blog -> delete();

		$confirmation = array();
		$confirmation['message'] = 'Item has been deleted!';

		return $this -> twig -> render('Admin/blog.html.twig', array('blogitems' => $blog -> getItems(), 'authors' => $user -> getUsers(), 'field_errors' => array(), 'confirmation' => $confirmation));

	}

}
