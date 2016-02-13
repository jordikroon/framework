<?php

namespace Application\Model;

use System\Framework\Model;

class BlogReply extends Model {

	private $blogItem;

	private $name;
	private $content;
	
	public function __construct($blogItem = 0) {

		$this -> database = $this -> getDatabase();
		$this -> setBlogItem($blogItem);

	}
	
	public function setBlogItem($blogItem) {

		if (is_int($blogItem)) {
			$this -> blogItem = $blogItem;
		} else {
			throw new \InvalidArgumentException('BlogItem should be an integer, got:' . gettype($blogItem));
		}
	}

	public function getBlogItem() {
		return $this -> blogItem;
	}

	public function setName($name) {
		$this -> name = $name;
	}

	public function getName() {
		return $this -> name;
	}
	
	public function setContent($content) {
		$this -> content = $content;	
	}
	
	public function getContent() {
		return $this -> content;
	}	
	
	public function countReplies() {
		$sth = $this -> database -> prepare('SELECT id
											FROM scms_blogreplies
											WHERE blog_id = ?');

		if ($sth -> execute(array($this -> getBlogItem()))) {

			return $sth -> rowCount();
			;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function getReplies() {
		$sth = $this -> database -> prepare('SELECT id, author, content, date_added
											FROM scms_blogreplies
											WHERE blog_id = ?');

		if ($sth -> execute(array($this -> getBlogItem()))) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}

	}

	public function add() {
		$sth = $this -> database -> prepare('INSERT INTO scms_blogreplies (blog_id, author, content, date_added) VALUES (?, ?, ?, NOW())');
		if ($sth -> execute(array($this -> getBlogItem(), $this -> getName(), $this -> getContent()))) {
			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}
}
