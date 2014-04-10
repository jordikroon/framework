<?php

$config = array();

$config['security']['roles'] = array(
									'Guest' => 0, 
									'User' => 1, 
									'Admin' => 2
								);
								
$config['security']['loginroute'] = 'login';


$config['security']['securedroutes'] = array(
										'admin' => array('Admin'),
										'admin-users' => array('Admin'),
										'admin-users-delete' => array('Admin'),
										'admin-users-edit' => array('Admin'),
										'admin-blog' => array('Admin'),
										'admin-blog-delete' => array('Admin'),
										'admin-blog-edit' => array('Admin'),
									);
	
$config['security']['checklogin'] = 'Auth_Login:checkLogin';