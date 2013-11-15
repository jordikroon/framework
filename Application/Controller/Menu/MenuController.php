<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @todo Parse url (@ = route, http(s) = external, other = internal)
 */
namespace Application\Controller\Menu;

use System\Framework\MainController;
use Application\Model\Menu;

class MenuController extends MainController {

	public function getItems() {
		
		$menu = new Menu;
		$menu -> setMenu('head');
		
		return $this -> twig -> render('Menu/menu.html.twig', array('menu' => $menu -> getItems()));
	}
	
}
		