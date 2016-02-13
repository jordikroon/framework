<?php

/**
 * @author Jordi Kroon
 * @version 1.1
 */
namespace Application\Model;

use System\Framework\Model;

class PortfolioTags extends Model {

	private $id;

	private $tags = array();
	
	public function __construct() {
		$this -> database = $this -> getDatabase();
	}

	public function setId($id) {
		$this -> id = $id;
	}

	public function getId() {
		return $this -> id;
	}

	public function addTag($tag) {
		$this -> tags[] = $tag;
	}

	public function getTags() {
		return $this -> tags;
	}

	
	public function createTags() {
		foreach($this -> getTags() AS $tag) {
			$sth = $this -> database -> prepare('INSERT INTO scms_portfoliotags (tag) VALUES (?)');
			if ($sth -> execute(array($tag))) {
				return true;
			} else {
				$pdoerr = $sth -> errorInfo();
				throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
			}	
		}
	}

	public function removeTag() {
		$sth = $this -> database -> prepare('DELETE FROM scms_portfoliotags WHERE tag = ?');

		if ($sth -> execute(array($this -> backid))) {

			return true;
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

			$sth = $this -> database -> prepare('SELECT id FROM scms_portfoliotags ' . $where);
			
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
