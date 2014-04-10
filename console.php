<?php

//use Symfony\Component\Console\Application;

require 'Config/autoload/autoload_namespaces.php';
$tokens = token_get_all('<?php echo; ?>');
 
print_r($tokens);
echo token_name(316);
/**
$application = new Application;
$application->add(new GreetCommand);
$application->run();
*/