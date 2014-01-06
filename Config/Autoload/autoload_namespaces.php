<?php

	$namespaces = array(
	
		#system extends 
		'Application\\System\\Controller\\SecurityController',
		
		#controller namespaces
		'Application\\Controller\\Home\\HomeController',
		'Application\\Controller\\Blog\\BlogController',
		'Application\\Controller\\Auth\\LoginController',
		'Application\\Controller\\Auth\\RegisterController',
		'Application\\Controller\\Menu\\MenuController',
		'Application\\Controller\\Admin\\DashboardController',
		'Application\\Controller\\Admin\\UserController',
		'Application\\Controller\\Admin\\BlogController',
				
		#model namespaces
		'Application\\Model\\User',
		'Application\\Model\\Auth',
		'Application\\Model\\Menu',
		'Application\\Model\\MenuItem',
		'Application\\Model\\Blog',
		'Application\\Model\\BlogReply',
		
		#system namespaces
		'System\\Framework\\Application',
		'System\\Framework\\Routing\\Router',
		'System\\Framework\\Routing\\Route',
		'System\\Framework\\MainController',
		'System\\Framework\\Database\\Database', 
		'System\\Framework\\Storage\\Session', 	
		'System\\Framework\\Form\\FormHandler',
		'System\\Framework\\Form\\FormValidator',
		'System\\Framework\\Model',
		'System\\Framework\\Config',
		'System\\Framework\\HTTP\\Request',
		'System\\Framework\\HTTP\\Response',
		'System\\Framework\\Exception\\ExceptionHandler',
		'System\\Framework\\Template\\Templating',
		'System\\Framework\\Template\\Extension\\CoreExtension',
		'System\\Framework\\Exception\\FileException',
		'System\\Framework\\Exception\\FileNotFoundException',
	);