<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */
 
namespace Application\Controller\Home;

use System\Framework\MainController;

Class HomeController extends MainController {

	public function index() {
		
$bb = new \UBBParser();

$content =  $bb->parse(<<<'EOT'

[quote=test]test[/quote]

[code]
<?php
echo 'test';
?>
[/code]

Zonder code tag:

<?php
echo 'hai';
?>

Originele probleem:

[code]
<?php
echo 'iets met code tag en open tag zonder sluit tag';
[/code]


Test.
EOT
,2);
		
		return $this -> twig -> render('Home/index.html.twig', array('content' => $content));
	}

}
