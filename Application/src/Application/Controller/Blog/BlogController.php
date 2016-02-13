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

		$reply = new BlogReply;
		$reply -> setBlogItem((int) $id);

		return $this -> twig -> render('Blog/blogitem.html.twig', array('item' => $blogItem, 'replies' => $reply -> getReplies(), 'countreplies' => $reply -> countReplies()));
	}

}
