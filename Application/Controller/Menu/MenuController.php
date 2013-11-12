<?php

namespace Application\Controller\Menu;

use System\Framework\MainController;

use Application\Model\Menu;
use Application\Model\MenuItem;

class MenuController extends MainController {

	public function getItems() {
		
		$menu = new Menu;
		$menu -> setMenu('head');
		
		
		$menuItem = new MenuItem;
		
		$menuItem -> setName('testName');
		$menuItem -> setLink('link');
		$menuItem -> setParent(0);
		
		$menu -> addItem($menuItem);
		
		
		print_r($menu -> getItems());
		
		return $this -> twig -> render('Menu/menu.html.twig');
	}
	
}
		