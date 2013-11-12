<?php
namespace Application\Model;

use System\Framework\Model;

Class MenuItem extends Model {
		
	private $name;
	
	private $link;
	
	private $parent;
	
	private $id;
	
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
	
	public function setParent($id) {
		$this -> parent = $id;
	}
	
	public function getName() {
		return $this -> name;
	}
	
	public function getLink() {
		return $this -> link;
	}
	
	public function getParent() {
		return $this -> parent;
	}
}
	