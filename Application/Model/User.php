<?php

namespace Application\Model;

use System\Framework\Model;
use System\Framework\Config;

class User extends Model {

	private $id;

	private $username;

	private $password;

	private $email;

	private $object;

	public function __construct() {
		$this -> database = $this -> getDatabase();
	}

	public function setId($id) {
		$this -> id = $id;
	}

	public function getId() {
		return $this -> id;
	}

	public function setUsername($username) {
		$this -> username = $username;
	}

	public function getUsername() {
		return $this -> username;
	}

	public function setPassword($password) {
		$this -> password = $password;
	}

	public function getPassword() {
		return $this -> password;
	}

	public function setEmail($email) {
		$this -> email = $email;
	}

	public function getEmail() {
		return $this -> email;
	}

	public function create() {
		$sth = $this -> database -> prepare('INSERT INTO scms_users (username, password, email) VALUES (?, ?, ?)');
		if ($sth -> execute(array($this -> getUsername(), $this -> hashPassword($this -> getPassword()), $this -> getEmail()))) {

			return true;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}

	public function update() {
		$sth = $this -> database -> prepare('UPDATE scms_users SET id=?, username=?, password=?, email=? WHERE id=?');

		if ($this -> getPassword() != $this -> object -> getPassword()) {
			$password = $this -> object -> getPassword();
		} else {
			$password = $this -> hashPassword($this -> getPassword());
		}
		if ($sth -> execute(array($this -> getId(), $this -> getUsername(), $password, $this -> getEmail(), $this -> object -> getId()))) {

			$this -> object = $this;
			// update with new information
			return true;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}

	public function delete() {
		$sth = $this -> database -> prepare('DELETE FROM scms_users WHERE id = ?');

		if ($sth -> execute(array($this -> object -> getId()))) {

			$this -> object = $this;

			return true;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
		}
	}

	public function read($id) {
		$sth = $this -> database -> prepare('SELECT id, username, password, email FROM scms_users WHERE id = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetch();

			$this -> setId($fetch['id']);
			$this -> setUsername($fetch['username']);
			$this -> setPassword($fetch['password']);
			$this -> setEmail($fetch['email']);

			$this -> object = $this;

			return $this;
		} else {
			throw new \PDOException('Could not execute query!' . $sth -> errorInfo());
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

			$sth = $this -> database -> prepare('SELECT id FROM scms_users ' . $where);
			
			print_r($array);
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

	public function hashPassword($password) {
		$config = new Config;

		$security = $config -> get('security');

		return sha1($security['salt'] . $password . $security['pepper']);
	}

}
