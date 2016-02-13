<?php

/**
 * @author Jordi Kroon
 * @version 1.1
 */
namespace Application\Model;

use System\Framework\Model;

class Portfolio extends Model {

	private $id;
	
	private $title;
	private $content;
	private $image;
	private $source;
	private $preview;
	private $released;
	private $cid;
	
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

	public function setTitle($title) {
		$this -> title = $title;
	}
	
	public function getTitle() {
		return $this -> title;
	}
	
	public function setContent($content) {
		$this -> content = $content;
	}
	
	public function getContent() {
		return $this -> content;
	}
	
	public function setImage($image) {
		$this -> image = $image;
	}
	
	public function getImage() {
		return $this -> image;
	}
	
	public function setSourceCode($source) {
		$this -> source = $source;
	}
	
	public function getSourceCode() {
		return $this -> source;
	}
	
	public function setPreview($preview) {
		$this -> preview = $preview;
	}
	
	public function getPreview() {
		return $this -> preview;
	}
	
	public function setReleased($released) {
		$this -> released = $released;	
	}
	
	public function getReleased() {
		return $this -> released;
	}
	
	public function setCategoryID($cid) {
		$this -> cid = $cid;
	}
	
	public function getCategoryID() {
		return $this -> cid;
	}
	
	public function create() {
		$sth = $this -> database -> prepare('INSERT INTO scms_portfolioitem (title, content, image, released, categoryid, preview, source) VALUES (?, ?, ?, ?, ?, ?, ?)');
		if ($sth -> execute(array($this -> getTitle(), $this -> getContent(), $this -> getImage(), $this -> getReleased(), $this -> getCategoryID(), $this -> getPreview(), $this -> getSourceCode()))) {
			return $this -> database -> lastInsertId();;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function update() {
		$sth = $this -> database -> prepare('UPDATE scms_portfolioitem SET id=?, title=?, content=?, image=?, released=?, categoryid=?, preview=?, source=? WHERE id=?');
				
		$prepare = array($this -> getId(), $this -> getTitle(), $this -> getContent(), $this -> getImage(), $this -> getReleased(), $this -> getCategoryID(), $this -> getPreview(), $this -> getSourceCode(), $this -> backid);
			
		if ($sth -> execute($prepare)) {
			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function delete() {
		$sth = $this -> database -> prepare('DELETE FROM scms_blog WHERE id = ?');

		if ($sth -> execute(array($this -> backid))) {

			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}

	public function read($id) {
		$sth = $this -> database -> prepare('SELECT id, title, content, image, released, categoryid, preview, source FROM scms_portfolioitem WHERE id = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetch();

			$this -> setId($fetch['id']);
			$this -> setTitle($fetch['title']);
			$this -> setContent($fetch['content']);
			$this -> setImage($fetch['image']);
			$this -> setSourceCode($fetch['source']);
			$this -> setPreview($fetch['preview']);
			$this -> setReleased($fetch['released']);
			$this -> setCategoryID($fetch['categoryid']);

			$this -> backid = $fetch['id'];
			return $this;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}
	
	public function getItemsBySlug($slug) {
		$sth = $this -> database -> prepare('SELECT scms_portfolioitem.id, title, content, image, released, categoryid, preview, source FROM scms_portfolioitem LEFT JOIN scms_portfoliocategory ON scms_portfolioitem.categoryid = scms_portfoliocategory.id WHERE scms_portfoliocategory.slug = ?');
		if ($sth -> execute(array($slug))) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}
	
	public function getItems() {
		$sth = $this -> database -> prepare('SELECT scms_portfolioitem.id, title, content, image, released, categoryid, preview, source FROM scms_portfolioitem LEFT JOIN scms_portfoliocategory ON scms_portfolioitem.categoryid = scms_portfoliocategory.id');
		if ($sth -> execute()) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}
	
	public function getItemById($id) {
		$sth = $this -> database -> prepare('SELECT id, title, content, image, released, categoryid, preview, source FROM scms_portfolioitem WHERE id = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetch(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}	
	
	public function getPortfolioTagsById($id) {
		$sth = $this -> database -> prepare('SELECT tagid, tag FROM scms_portfolioitemtags INNER JOIN scms_portfoliotags on tagid = scms_portfoliotags.id WHERE itemid = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}
	
	public function getPortfolioTags() {
		$sth = $this -> database -> prepare('SELECT id, tag FROM scms_portfoliotags');
		if ($sth -> execute()) {

			$fetch = $sth -> fetchAll(\PDO::FETCH_ASSOC);

			return $fetch;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}
	
	
	public function getJsonTags(array $tags) {
		$json = array();
		foreach($tags AS $key => $tag) {
			$json[$key]['id'] = $tag['id'];
			$json[$key]['label'] = $tag['tag'];
			$json[$key]['value'] = $tag['tag'];
		}	
		
		return json_encode($json);
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
}
