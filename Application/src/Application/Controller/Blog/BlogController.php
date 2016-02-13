<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */

namespace Application\Controller\Blog;

use System\Framework\MainController;
use Application\Model\Blog;
use Application\Model\BlogReply;
use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;
use System\Framework\HTTP\Response;

class BlogController extends MainController {

	public function index() {

		$blog = new Blog;


		return $this -> twig -> render('Blog/blog.html.twig', array('blogitems' => $blog -> getItems()));
	}

	public function item($id) {

		$blog = new Blog;
		$blog -> read($id);

		$blogItem = array();
		$blogItem['id'] = $blog -> getId();
		$blogItem['author'] = $blog -> getAuthor();
		$blogItem['title'] = $blog -> getTitle();
		$blogItem['content'] = $blog -> getContent();
		$blogItem['date_added'] = $blog -> getDateAdded();

		if (!empty($blogItem['id'])) {
			$reply = new BlogReply;
			$reply -> setBlogItem((int) $id);

			$form = new FormHandler;

			if ($form -> isMethod('post')) {

				$fields = $form -> getFields(array('name', 'reply'));

				$validator = new FormValidator($fields);

				$validator -> rule('required', array('name', 'reply'));
				if (!$validator -> validate()) {
					$content['error'] = 'Please fill in all the fields!';
				} else {
					$reply -> setName($fields['name']);
					$reply -> setContent($fields['reply']);

					if ($reply -> add()) {
						$content['succes'] = 'Your reply has been added!';
					} else {
						$content['error'] = 'Your reply could not been added!';
					}
				}
			}

			return $this -> twig -> render('Blog/blogitem.html.twig', array('item' => $blogItem, 'replies' => $reply -> getReplies(), 'countreplies' => $reply -> countReplies()));

		} else {
			$response = new Response;
			
			$response -> redirect(404);
		}
	}

	public function recent() {
		$blog = new Blog();
		$blog -> getRecentPosts();
		$content['posts'] = $blog -> getRecentPosts();
		
		return $this -> twig -> render('Core/recentposts.html.twig', array('content' => $content));
	
	}
}
