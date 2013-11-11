<?php
	$routes = array(
		array('home', 
					'','Home_Home:index'), // empty = /
					
		array('login', 
					'auth/login','Auth_Login:index'),

		array('logout', 
					'auth/logout','Auth_Login:logOut'),	
									
		array('register', 
					'auth/register','Auth_Register:index'),
	);