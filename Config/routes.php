<?php
	$routes = array(
		array('contact_page', 
					'home','Home_Home:index'),
					
		array('login', 
					'auth/login','Auth_Login:index'),

		array('logout', 
					'auth/logout','Auth_Login:logOut'),	
									
		array('register', 
					'auth/register','Auth_Register:index'),
	);