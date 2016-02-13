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
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function addItem($menuitem) {
		if (is_object($menuitem) && $menuitem instanceof MenuItem) {
			
			$sth = $this -> database -> prepare('INSERT INTO scms_menu (mname, lname, link, parent) VALUES (?, ?, ?, ?)');
			if ($sth -> execute(array($this -> getMenu(), $menuitem -> getName(), $menuitem -> getLink(), $menuitem -> getParent()))) {

				return true;
			} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
			}
			 
		} else {
			throw new \InvalidArgumentException('Parameter should be an instance of Menuitem!');
		}
	}

	public function removeItem($menuitem) {
		if (is_object($menuitem) && $menuitem instanceof MenuItem) {
			
			$sth = $this -> database -> prepare('DELETE from scms_menu WHERE mname = ? AND id = ?');
			if ($sth -> execute(array($this -> getMenu(), $menuitem -> getId()))) {

				return true;
			} else {
				throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
			}
			 
		} else {
			throw new \InvalidArgumentException('Parameter should be an instance of Menuitem!');
		}
	}
	
	public function updateItem($menuitem) {
		if (is_object($menuitem) && $menuitem instanceof MenuItem) {
			
			$sth = $this -> database -> prepare('UPDATE scms_menu SET id=?, mname=?, lname=?, link=? WHERE id=?');
			if ($sth -> execute(array($menuitem -> getId(), $this -> getMenu(), $menuitem -> getName(), $menuitem -> getLink(), $menuitem -> getId()))) {
				return true;
			} else {
				throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
			}
			 
		} else {
			throw new \InvalidArgumentException('Parameter should be an instance of Menuitem!');
		}
	}
	
	public function itemExists($array) {

		$where = 'WHERE ';

		if (is_array($array)) {

			$i = 0;
			foreach ($array AS $key => $value) {

				if ($i > 0) {
					$where .= ' AND ';
				}

				$where .= $key . ' = ?';

				$i++;
			}

			$sth = $this -> database -> prepare('SELECT id FROM scms_menu ' . $where);
			
			if ($sth -> execute(array_values($array))) {
				if($sth->rowCount() >= 1) {
					return true;
				}
			} else {
				throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
			}

		} else {
			throw new \InvalidArgumentException('Parameter should be an array! Got: ' . gettype($array));
		}

	}
}
