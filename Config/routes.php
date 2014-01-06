<?php
	$routes = array(
		array('home', 
					'','Home_Home:index'), // empty = /
					
		array('login', 
					'auth/login/<#type>','Auth_Login:index'),

		array('logout', 
					'auth/logout','Auth_Login:logOut'),	
									
		array('register', 
					'auth/register','Auth_Register:index'),
					
		array('blog', 
					'blog','Blog_Blog:index'),

		array('blog-item', 
					'blog/item/<:id>/<#title>','Blog_Blog:item'),
					
		array('blog-item-add', 
					'blog/item/<:id>/<#title>/add','Blog_Blog:additem'),	
						
		array('blog-item-edit', 
					'blog/item/<:id>/<#title>/edit','Blog_Blog:edititem'),
					
		array('blog-item-remove', 
					'blog/item/<:id>/<#title>/remove','Blog_Blog:removeitem'),	
								
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