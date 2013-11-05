<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once '../autoload.php';
require_once '../config/autoload/autoload_namespaces.php';
require_once '../config/autoload/autoload_vendor.php';


if (isset($namespaces) && is_array($namespaces)) {
	foreach ($namespaces AS $namespaces) {
		$classLoader = new ClassLoader($namespaces);
		$classLoader -> register();
	}
}

if (isset($vendors) && is_array($vendors)) {
	foreach ($vendors AS $vendor) {
		$classLoader = new ClassLoader($vendor);
		$classLoader->setIncludePath('Vendor');
		$classLoader -> register();
	}
}

$classLoader -> registerFiles();

$application = new \System\Framework\Application;

echo $application -> runApp();
