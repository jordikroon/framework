<?php

namespace Application\Controller\Portfolio;

use System\Framework\Maincontroller;

use Application\Model\Portfolio;
use Application\Model\PortfolioCategory;
use System\Framework\HTTP\Response;

class PortfolioController extends Maincontroller {

	public function index() {
		echo 'maybe later';
	}

	public function getItemsByCategory($category) {

		
		$portfolioCategory = new PortfolioCategory;
		$categoryinfo = $portfolioCategory -> getCategoryBySlug($category);

		if (empty($categoryinfo)) {
			$response = new Response;
			$response -> redirect(404);
		} else {
			$portfolio = new Portfolio;

			$items = array();
			foreach ($portfolio -> getItemsBySlug($category) AS $key => $item) {
				$items[$key] = $item;
				$items[$key]['tags'] = $portfolio -> getPortfolioTagsById($item['id']);
			}
			return $this -> twig -> render('Portfolio/portfolio.html.twig', array('info' => $categoryinfo, 'items' => $items));
		}

	}

	public function getItemsBySlug($category, $id) {

		$portfolioCategory = new PortfolioCategory;
		$categoryinfo = $portfolioCategory -> getCategoryBySlug($category);

		if (empty($categoryinfo)) {
			$response = new Response;
			$response -> redirect(404);
		} else {
			$portfolio = new Portfolio;

			$item = $portfolio -> getItemById($id);
		
			if (!empty($item)) {
				$item['tags'] = $portfolio -> getPortfolioTagsById($id);
				return $this -> twig -> render('Portfolio/portfolioitem.html.twig', array('info' => $categoryinfo, 'item' => $item));
			} else {
				$response = new Response;
				$response -> redirect(404);
			}
		}
	}

}
