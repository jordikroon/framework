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
					
		array('blog', 
					'blog','Blog_Blog:index'),
					
		array('admin', 
					'admin/dashboard','Admin_Dashboard:index'),
					
		array('admin-users', 
					'admin/users','Admin_User:index'),
					
		array('admin-users-delete', 
					'admin/users/delete/<:id>','Admin_User:delete'),

		array('admin-users-edit', 
					'admin/users/edit/<:id>','Admin_User:edit'),
					
		array('admin-blog', 
					'admin/blog','Admin_Blog:index'),
				
		array('admin-blog-delete', 
					'admin/blog/delete/<:id>','Admin_Blog:delete'),

		array('admin-blog-edit', 
					'admin/blog/edit/<:id>','Admin_Blog:edit'),
					
	);