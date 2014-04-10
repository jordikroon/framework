<?php

namespace Application\Model;

use System\Framework\Model;
use System\Framework\Config;

class User extends Model {

	private $id;

	private $username;

	private $fullname;
	
	private $password;

	private $email;

	private $role = 0;
	
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

	public function setUsername($username) {
		$this -> username = $username;
	}

	public function getUsername() {
		return $this -> username;
	}

	public function setFullname($fullname) {
		$this -> fullname = $fullname;
	}

	public function getFullname() {
		return $this -> fullname;
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

	public function setRole($role) {
		$this -> role = $role;
	}

	public function getRole() {
		return $this -> role;
	}
	
	public function create() {
		$sth = $this -> database -> prepare('INSERT INTO scms_users (username, password, email, role, fullname) VALUES (?, ?, ?, ?, ?)');
		if ($sth -> execute(array($this -> getUsername(), $this -> hashPassword($this -> getPassword()), $this -> getEmail(), $this -> getRole(), $this -> getFullname()))) {
			$this -> setId($this -> database -> lastInsertId());
			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function update() {
		$sth = $this -> database -> prepare('UPDATE scms_users SET id=?, username=?, password=?, email=?, role=?, fullname=? WHERE id=?');
				
			$password = $this -> hashPassword($this -> getPassword());
			$prepare = array($this -> getId(), $this -> getUsername(), $password, $this -> getEmail(), $this -> getRole(), $this -> getFullname(), $this -> backid);
			
			if($this -> getPassword() == '') {
				$sth = $this -> database -> prepare('UPDATE scms_users SET id=?, username=?, email=?, role=?, fullname=? WHERE id=?');
				$prepare = array($this -> getId(), $this -> getUsername(), $this -> getEmail(), $this -> getRole(), $this -> getFullname(), $this -> backid);
			}
			
		if ($sth -> execute($prepare)) {
			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function delete() {
		$sth = $this -> database -> prepare('DELETE FROM scms_users WHERE id = ?');

		if ($sth -> execute(array($this -> backid))) {

			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function read($id) {
		$sth = $this -> database -> prepare('SELECT id, username, password, email, role, fullname FROM scms_users WHERE id = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetch();

			$this -> setId($fetch['id']);
			$this -> setUsername($fetch['username']);
			$this -> setEmail($fetch['email']);
			$this -> setFullname($fetch['fullname']);
			$this -> setRole($fetch['role']);
			$this -> backid = $fetch['id'];
			return $this;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new PDOException('Could not execute query, ' . $pdoerr[2]);
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

	public function getUsers() {
		$sth = $this -> database -> prepare('SELECT id, username, password, email, role, fullname FROM scms_users');
		if ($sth -> execute()) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}	
	
	public function hashPassword($password) {
		$config = new Config;
		$config -> loadFile(__dir__ . '/../../Config/application.php');
		$security = $config -> get('security');

		return sha1($security['salt'] . $password . $security['pepper']);
	}

}
