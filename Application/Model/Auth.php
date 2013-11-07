<?php
namespace Application\Model;

use System\Framework\Model;
use System\Framework\Config;
use System\Framework\Storage\Session;

Class Auth extends Model {

	private $database;

	private $userid;

	public function __construct() {
		$this -> database = $this -> getDatabase();
	}

	public function check($user) {
		if (is_object($user)) {
			
			$sth = $this -> database -> prepare('SELECT id FROM scms_users WHERE username = ? AND password = ?');
			$sth -> execute(array($user -> getUsername(), $user -> hashPassword($user -> getPassword())));

			if ($sth -> rowCount() == 1) {
				$result = $sth -> fetch(\PDO::FETCH_ASSOC);
				$this -> userid = $result['id'];

				return true;
			}
		}
	}

	public function getKey() {
		if (empty($this -> userid)) {
			throw new ErrorException('$this -> userid cannot be empty!');
		} else {
			$key = uniqid($this -> userid);

			$sth = $this -> database -> prepare('UPDATE scms_users SET authkey = ? WHERE id = ?');
			if ($sth -> execute(array($key, $this -> userid))) {
				return $key;
			} else {
				throw new PDOException('Failed to update authkey!');
			}

		}
	}

	public function isAuthenticated() {

		$session = new Session;
		if ($session -> exists('key')) {
			$sth = $this -> database -> prepare('SELECT id FROM scms_users WHERE authkey = ?');
			if ($sth -> execute(array($session -> get('key')))) {

				if ($sth -> rowCount() == 1) {
					return true;
				}
			} else {
				throw new PDOException('Failed to get authkey!');
			}
		}

	}


}
