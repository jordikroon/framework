<?php

	$namespaces = array(
		#controller namespaces
		'Application\\Controller\\Home\\HomeController',
		'Application\\Controller\\Auth\\LoginController',
		'Application\\Controller\\Auth\\RegisterController',
				
		#model namespaces
		'Application\\Model\\User',
		'Application\\Model\\Auth',
		
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
	);