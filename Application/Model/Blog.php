<?php

/**
 * @author Jordi Kroon
 * @version 1.1
 */
namespace Application\Model;

use System\Framework\Model;

class Blog extends Model {

	private $id;
	
	private $author;
	
	private $title;
	
	private $content;
	
	private $publish;
	
	private $backid;
	
	private $dateAdded;
	
	public function __construct() {
		$this -> database = $this -> getDatabase();
	}

	public function setId($id) {
		$this -> id = $id;
	}

	public function getId() {
		return $this -> id;
	}

	public function setAuthor($author) {
		$this -> author = $author;
	}

	public function getAuthor() {
		return $this -> author;
	}

	public function setTitle($title) {
		$this -> title = $title;
	}

	public function getTitle() {
		return $this -> title;
	}

	public function setContent($content) {
		$this -> content = $content;
	}

	public function getContent() {
		return $this -> content;
	}

	public function setPublished($publish) {
		$this -> publish = (int) $publish;
	}

	public function getPublished() {
		return $this -> publish;
	}

	private function setDateAdded($added) {
		$this -> dateAdded = $added;
	}
		
	public function getDateAdded() {
		return $this -> dateAdded;
	}
	
	public function create() {
		$sth = $this -> database -> prepare('INSERT INTO scms_blog (title, content, published, author_id, date_added) VALUES (?, ?, ?, ?, NOW())');
		if ($sth -> execute(array($this -> getTitle(), $this -> getContent(), $this -> getPublished(), $this -> getAuthor()))) {
			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function update() {
		$sth = $this -> database -> prepare('UPDATE scms_blog SET id=?, title=?, content=?, published=?, author_id=? WHERE id=?');
				
		$prepare = array($this -> getId(), $this -> getTitle(), $this -> getContent(), $this -> getPublished(), $this -> getAuthor(), $this -> backid);
			
		if ($sth -> execute($prepare)) {
			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function delete() {
		$sth = $this -> database -> prepare('DELETE FROM scms_blog WHERE id = ?');

		if ($sth -> execute(array($this -> backid))) {

			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function read($id) {
		$sth = $this -> database -> prepare('SELECT id, title, content, published, author_id, date_added FROM scms_blog WHERE id = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetch();

			$this -> setId($fetch['id']);
			$this -> setAuthor($fetch['author_id']);
			$this -> setTitle($fetch['title']);
			$this -> setContent($fetch['content']);
			$this -> setPublished($fetch['published']);
			$this -> setDateAdded($fetch['date_added']);
			
			$this -> backid = $fetch['id'];
			return $this;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function getItems() {
		$sth = $this -> database -> prepare('SELECT id, title, content, published, author_id, date_added, (
												SELECT COUNT( blog_id )
												FROM scms_blogreplies
												WHERE scms_blogreplies.blog_id = scms_blog.id
											)  as replies
											FROM scms_blog');
		if ($sth -> execute()) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}	
	
}
