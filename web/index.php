<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../autoload.php';
require_once '../Config/Autoload/autoload_namespaces.php';

require_once '../Vendor/autoload.php';

/***************************************************************************\
 *                                INIT APPLICATION                         *
\***************************************************************************/

if (isset($namespaces) && is_array($namespaces)) {
	foreach ($namespaces AS $namespace) {
		$classLoader = new ClassLoader($namespace);
		$classLoader -> register();
	}
}

echo \System\Framework\Application::run();
