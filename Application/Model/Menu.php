<?php
namespace Application\Model;

use System\Framework\Model;

Class Menu extends Model {

	private $menu;

	private $database;

	public function __construct($menu = '') {
		$this -> database = $this -> getDatabase();

		$this -> setMenu($menu);
	}

	public function getMenu() {
		return $this -> menu;
	}

	public function setMenu($menu) {
		$this -> menu = $menu;
	}

	public function getItems() {
		$sth = $this -> database -> prepare('SELECT id, mname, lname, link, parent FROM scms_menu WHERE mname = ?');
		if ($sth -> execute(array($this -> getMenu()))) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}
	
	public function addItem($menuitem) {
		var_dump($menuitem);
	}
}
