<?php

namespace Application\Controller\Menu;

use System\Framework\MainController;

use Application\Model\Menu;

class MenuController extends MainController {

	public function getItems() {
		return $this -> twig -> render('Menu/menu.html.twig');
	}
	
}
		