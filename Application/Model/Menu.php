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
		if (is_object($menuitem) && $menuitem instanceof MenuItem) {
			
			$sth = $this -> database -> prepare('INSERT INTO scms_menu (mname, lname, link, parent) VALUES (?, ?, ?, ?)');
			if ($sth -> execute(array($this -> getMenu(), $menuitem -> getName(), $menuitem -> getLink(), $menuitem -> getParent()))) {

				return true;
			} else {
				throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
			}
			 
		} else {
			throw new \InvalidArgumentException('Parameter should be an instance of Menuitem!');
		}
	}

}
