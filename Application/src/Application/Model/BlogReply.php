<?php

namespace Application\Model;

use System\Framework\Model;

class BlogReply extends Model {

	private $blogItem;

	public function __construct($blogItem = 0) {

		$this -> database = $this -> getDatabase();
		$this -> setBlogItem($blogItem);

	}

	public function setBlogItem($blogItem) {

		if (is_int($blogItem)) {
			$this -> blogItem = $blogItem;
		} else {
			throw new \InvalidArgumentException('BlogItem should be an integer, got:' . gettype($blogItem));
		}
	}

	public function getBlogItem() {
		return $this -> blogItem;
	}

	public function countReplies() {
		$sth = $this -> database -> prepare('SELECT id
											FROM scms_blogreplies
											WHERE blog_id = ?');

		if ($sth -> execute(array($this -> getBlogItem()))) {

			return $sth -> rowCount();
			;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function getReplies() {
		$sth = $this -> database -> prepare('SELECT scms_blogreplies.id, fullname, content, date_added, created,  (
												SELECT COUNT( scms_blogreplies.id )
												FROM scms_blogreplies
												WHERE scms_users.id = author_id
											) AS posts
											FROM scms_blogreplies
											LEFT JOIN scms_users ON author_id = scms_users.id
											WHERE blog_id = ?');

		if ($sth -> execute(array($this -> getBlogItem()))) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}

	}

}
