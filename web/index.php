<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../autoload.php';
require_once '../config/autoload/autoload_namespaces.php';

require_once '../Vendor/autoload.php';

/***************************************************************************\
 *                                INIT APPLICATION                         *
\***************************************************************************/

if (isset($namespaces) && is_array($namespaces)) {
	foreach ($namespaces AS $namespaces) {
		$classLoader = new ClassLoader($namespaces);
		$classLoader -> register();
	}
}


echo \System\Framework\Application::run();
