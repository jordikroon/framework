<?php

/**
 * @author Jordi Kroon
 * @version 1.1
 */
namespace Application\Model;

use System\Framework\Model;

class Settings extends Model {

	private $id;
	private $sitetitle;
	private $sitedescription;
	private $spotlighttitle;
	private $spotlightdescription;
	private $spotlighturl;
	private $adminname;
	private $adminemail;
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
	
	public function setSiteTitle($sitetitle) {
		$this -> sitetitle = $sitetitle;
	}
	
	public function getSiteTitle() {
		return $this -> sitetitle;
	}
	
	public function setSiteDescription($sitedescription) {
		$this -> sitedescription = $sitedescription;
	}
	
	public function getSiteDescription() {
		return $this -> sitedescription;
	}
	
	public function setSpotlightTitle($spotlighttitle) {
		$this -> spotlighttitle = $spotlighttitle;
	}
	
	public function getSpotlightTitle() {
		return $this -> spotlighttitle;
	}
	
	public function setSpotlightDescription($spotlightdescription) {
		$this -> spotlightdescription = $spotlightdescription;
	}
	
	public function getSpotlightDescription() {
		return $this -> spotlightdescription;
	}

	public function setSpotlightUrl($spotlighturl) {
		$this -> spotlighturl = $spotlighturl;
	}
	
	public function getSpotlightUrl() {
		return $this -> spotlighturl;
	}
	
	public function setAdminName($adminname) {
		$this -> adminname = $adminname;
	}
	
	public function getAdminName() {
		return $this -> adminname;
	}
	
	public function setAdminEmail($adminemail) {
		$this -> adminemail = $adminemail;
	}
	
	public function getAdminEmail() {
		return $this -> adminemail;
	}
	
	public function update() {
		$sth = $this -> database -> prepare('INSERT INTO scms_settings (id, sitetitle, sitedescription, spotlighttitle, spotlightdescription, spotlighturl, adminname, adminemail) VALUES (?,?,?,?,?,?,?,?)
  											 ON DUPLICATE KEY UPDATE id = ?, sitetitle=?, sitedescription=?, spotlighttitle=?, spotlightdescription=?, spotlighturl=?, adminname=?, adminemail=?;
											');
				
		$prepare = array(
			$this -> backid, 
			$this -> getSiteTitle(), 
			$this -> getSiteDescription(), 
			$this -> getSpotlightTitle(), 
			$this -> getSpotlightDescription(), 
			$this -> getSpotlightUrl(), 
			$this -> getAdminName(), 
			$this -> getAdminEmail());
			
		$prepare = array_merge($prepare, $prepare);
		if ($sth -> execute($prepare)) {
			return true;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}	

	public function read($id) {
		$sth = $this -> database -> prepare('SELECT id, sitetitle, sitedescription, spotlighttitle, spotlightdescription, spotlighturl, adminname, adminemail FROM scms_settings WHERE id = ?');
		if ($sth -> execute(array($id))) {

			$fetch = $sth -> fetch();

			$this -> setId($fetch['id']);
			$this -> setSiteTitle($fetch['sitetitle']);
			$this -> setSiteDescription($fetch['sitedescription']);
			$this -> setSpotlightTitle($fetch['spotlighttitle']);
			$this -> setSpotlightDescription($fetch['spotlightdescription']);
			$this -> setSpotlightUrl($fetch['spotlighturl']);
			$this -> setAdminName($fetch['adminname']);
			$this -> setAdminEmail($fetch['adminemail']);
			
			$this -> backid = $id;
			
			return $this;
		} else {
			$pdoerr = $sth -> errorInfo();
			throw new \PDOException('Could not execute query, ' . $pdoerr[2]);
		}
	}
}
