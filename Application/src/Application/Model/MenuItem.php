<?php
namespace Application\Model;

use System\Framework\Model;

Class MenuItem extends Model {
		
	private $name;
	
	private $link;
	
	private $parent;
	
	private $id;
	
	private $database;

	public function __construct($menu = '') {
		$this -> database = $this -> getDatabase();
	}
	
	public function setId($id) {
		$this -> id = $id;
	}

	public function getId() {
		return $this -> id;
	}
		
	public function setName($name) {
		$this -> name = $name;
	}
	
	public function setLink($link) {
		$this -> link = $link;
	}
	
	public function getName() {
		return $this -> name;
	}
	
	public function getLink() {
		return $this -> link;
	}
	
	public function readItem($id) {
		$sth = $this -> database -> prepare('SELECT id, mname, lname, link FROM scms_menu WHERE id = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetch();

			$this -> setId($fetch['id']);
			$this -> setName($fetch['lname']);
			$this -> setLink($fetch['link']);
			return $this;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}
}
