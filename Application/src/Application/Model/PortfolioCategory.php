<?php

/**
 * @author Jordi Kroon
 * @version 1.1
 */
namespace Application\Model;

use System\Framework\Model;

class PortfolioCategory extends Model {

	private $id;

	private $name;
	
	private $slug;
	
	public function __construct() {
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

	public function getName() {
		return $this -> name;
	}

	public function setSlug($slug) {
		$this -> slug = $slug;
	}

	public function getSlug() {
		return $this -> slug;
	}
	
	public function create() {
		$sth = $this -> database -> prepare('INSERT INTO scms_portfoliocategory (category, slug ) VALUES (?, ?)');
		if ($sth -> execute(array($this -> getName(), $this -> getSlug()))) {
			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function update() {
		$sth = $this -> database -> prepare('UPDATE scms_portfoliocategory SET id=?, category=?, slug=? WHERE id=?');
				
		$prepare = array($this -> getId(), $this -> getName(), $this -> getSlug(), $this -> backid);
			
		if ($sth -> execute($prepare)) {
			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function delete() {
		$sth = $this -> database -> prepare('DELETE FROM scms_portfoliocategory WHERE id = ?');

		if ($sth -> execute(array($this -> backid))) {

			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function read($id) {
		$sth = $this -> database -> prepare('SELECT id, category, slug FROM scms_portfoliocategory WHERE id = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetch();

			$this -> setId($fetch['id']);
			$this -> setName($fetch['category']);
			$this -> setSlug($fetch['slug']);
			
			$this -> backid = $fetch['id'];
			return $this;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function getCategories() {
		$sth = $this -> database -> prepare('SELECT id, category, slug FROM scms_portfoliocategory');
		if ($sth -> execute()) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}
	
	public function getCategoryBySlug($slug) {
		$sth = $this -> database -> prepare('SELECT id, category, slug FROM scms_portfoliocategory WHERE slug = ?');
		if ($sth -> execute(array($slug))) {

			$fetch = $sth -> fetch(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}	
	
	
	public function exists($array) {

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

			$sth = $this -> database -> prepare('SELECT id FROM scms_portfoliocategory ' . $where);
			
			if ($sth -> execute(array_values($array))) {
				if($sth->rowCount() >= 1) {
					return true;
				}
			} else {
			$pdoerr = $sth -> errorInfo();
			throw new PDOException('Could not execute query, ' . $pdoerr[2]);
			}

		} else {
			throw new \InvalidArgumentException('Parameter should be an array! Got: ' . gettype($array));
		}

	}
	
}
