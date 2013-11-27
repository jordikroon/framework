<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @todo testen of dit werkt:)
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
	
	public function create() {
		$sth = $this -> database -> prepare('INSERT INTO scms_blog (title, content, published, author_id) VALUES (?, ?, ?, ?)');
		if ($sth -> execute(array($this -> getTitle(), $this -> getContent(), $this -> getPublished(), $this -> getAuthor()))) {
			return true;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}

	public function update() {
		$sth = $this -> database -> prepare('UPDATE scms_blog SET id=?, title=?, content=?, published=?, author_id=? WHERE id=?');
				
		$prepare = array($this -> getTitle(), $this -> getContent(), $this -> getPublished(), $this -> getAuthor(), $this -> backid);
			
		if ($sth -> execute($prepare)) {
			return true;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}

	public function delete() {
		$sth = $this -> database -> prepare('DELETE FROM scms_blog WHERE id = ?');

		if ($sth -> execute(array($this -> backid))) {

			return true;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}

	public function read($id) {
		$sth = $this -> database -> prepare('SELECT id, title, content, published, author_id FROM scms_blog id = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetch();

			$this -> setId($fetch['id']);
			$this -> setAuthor($fetch['author_id']);
			$this -> setTitle($fetch['title']);
			$this -> setContent($fetch['content']);
			$this -> setPublished($fetch['published']);
			
			$this -> backid = $fetch['id'];
			return $this;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}

	public function getItems() {
		$sth = $this -> database -> prepare('SELECT id, title, content, published, author_id');
		if ($sth -> execute()) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}	
	
}
