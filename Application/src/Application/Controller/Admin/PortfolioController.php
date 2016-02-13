<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS\Admin
 */

namespace Application\Controller\Admin;

use System\Framework\MainController;

use Application\Model\Auth;
use Application\Model\User;
use Application\Model\Portfolio;
use Application\Model\PortfolioCategory;
use Application\Model\PortfolioTags;

use System\Framework\Form\FormHandler;
use System\Framework\Form\FormValidator;
use System\Framework\Security;

class PortfolioController extends MainController {

	public function index() {

		$portfolio = new Portfolio;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();

		if ($form -> isMethod('post')) {
			
			$fields = $form -> getFields(array('title', 'category', 'content', 'image', 'tag', 'source', 'preview', 'released', '_token'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('title', 'category', 'content', 'image', 'tag'));
			
			if(!empty($fields['released'])) {
				$validator -> rule('date', 'released');
			}
			if(!empty($fields['source'])) {
				$validator -> rule('url', 'source');
			}
			if(!empty($fields['preview'])) {
				$validator -> rule('url', 'preview');
			}	
			
			if(!empty($fields['image']['name'])) {
				$storage = new \Upload\Storage\FileSystem(__dir__ . '/../../../public_html/files/');
				$file = new \Upload\File('image', $storage);
				
				$file -> setName(uniqid());
				$file -> addValidations(array(
				    new \Upload\Validation\Mimetype(array('image/png', 'image/jpeg', 'image/pjpeg')),
				    new \Upload\Validation\Size('2M')
				));
				
				$data = $file -> getDimensions();
				
				
				if($data['width'] != 234 || $data['height'] != 165) {
					$error['file']['dimensions'] = 'Dimensions should be 234x165 pixels!';
				}
			} else {
				$error['file']['image'] = 'Image is empty!';
			}
			
			$security = new Security;
			if(!$security -> checkSignature($fields['_token'])) {
				$error['csrf'][] = 'Invalid token provided!';
			}
			
			if(!is_array($fields['tag']) || count($fields['tag']) < 1) {
				$error['tag'][] = 'Please add tags to your project!';	
			}
			
			echo \DateTime::createFromFormat('Y/m/d', $fields['released']);
			echo $fields['released'];
			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {
				
				if($file->upload()) {
					
					$portfoliotags = new PortfolioTags;
				
					foreach($fields['tag'] AS $tag) {
						$childfields = explode(' ', str_replace(array(';',','), ' ', $tag));
						
						foreach($childfields AS $child) {
							if(!$portfoliotags -> exists(array('tag' => trim($child)))) {
								$portfoliotags -> addTag(trim($child));
							}
						}
					}
					
					$portfoliotags -> createTags();
					
					$portfolio -> setTitle($fields['title']);
					$portfolio -> setContent($fields['content']);
					$portfolio -> setImage($file -> getNameWithExtension());
					$portfolio -> setSourceCode($fields['source']);
					$portfolio -> setPreview($fields['preview']);
					

					$portfolio -> setReleased(\DateTime::createFromFormat('Y/m/d', $fields['released']));
					$portfolio -> setCategoryID($fields['category']);
					$id = $portfolio -> create();
					
					$confirmation['message'] = 'Project has been created!';
					
					$form -> unsetAll();	
				} else {
					$error = $file -> getErrors();
				}

			}
		}
			
		return $this -> twig -> render('Admin/portfolio.html.twig', array('tags' => $portfolio -> getJsonTags($portfolio -> getPortfolioTags()), 'portfolioitems' => $portfolio -> getItems(), 'categories' => $portfolio -> getCategories(), 'field_errors' => $error, 'confirmation' => $confirmation));
	}
	
	public function edit($id) {

		$portfolio = new Portfolio;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();

		$portfolio -> read($id);

		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('title', 'category', 'content', 'image', 'tag', 'source', 'preview', 'released', '_token'));

			$security = new Security;
			if(!$security -> checkSignature($fields['_token'])) {
				$error['csrf'][] = 'Invalid token provided!';
			} 
			

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('title', 'category', 'content', 'image', 'tag'));
			
			if(!empty($fields['released'])) {
				$validator -> rule('date', 'released');
			}
			if(!empty($fields['source'])) {
				$validator -> rule('url', 'source');
			}
			if(!empty($fields['preview'])) {
				$validator -> rule('url', 'preview');
			}	
			
			if(isset($fields['image']['name']) && !empty($fields['image']['name'])) {
				echo __dir__ . '/../../../public_html/files/';
				$storage = new \Upload\Storage\FileSystem(__dir__ . '/../../../public_html/files/');
				$file = new \Upload\File('image', $storage);
				
				$file -> setName(uniqid());
				$file -> addValidations(array(
				    new \Upload\Validation\Mimetype(array('image/png', 'image/jpeg', 'image/pjpeg')),
				    new \Upload\Validation\Size('2M')
				));
				
				$data = $file -> getDimensions();
				
				
				if($data['width'] != 234 || $data['height'] != 165) {
					$error['file']['dimensions'] = 'Dimensions should be 234x165 pixels!';
				}
			} 
			
			
			if(!is_array($fields['tag']) || count($fields['tag']) < 1) {
				$error['tag'][] = 'Please add tags to your project!';	
			}
			
			
			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {
				
				if(!empty($fields['image']['name'])) {
					$imagecheck = $file -> upload();
				} else {
					$imagecheck = true;
				}
				if($imagecheck) {
					
					$portfoliotags = new PortfolioTags;
					
					foreach($fields['tag'] AS $tag) {
						if(!$portfoliotags -> exists(array('tag' => $tag))) {
							$portfoliotags -> addTag($tag);
						}
					}
					
					$portfoliotags -> createTags();
					
					$portfolio -> setTitle($fields['title']);
					$portfolio -> setContent($fields['content']);
					
					if(!empty($fields['image']['name']) ) {
						$portfolio -> setImage($file -> getNameWithExtension());
					}
					$portfolio -> setSourceCode($fields['source']);
					$portfolio -> setPreview($fields['preview']);
					$portfolio -> setReleased($fields['released']);
					$portfolio -> setCategoryID($fields['category']);
					$portfolio -> update();
	
					$confirmation['message'] = 'portfolio item has been edited!';
					
					$form -> unsetAll();	
				} else {
					$error = $file -> getErrors();
				}

			}
		}
		
		
		return $this -> twig -> render('Admin/portfolio.html.twig', 
					array('tags' => $portfolio -> getJsonTags($portfolio -> getPortfolioTags()), 
						  'portfolioitems' => $portfolio -> getItems(),
						  'categories' => $portfolio -> getCategories(),
						  'field_errors' => $error, 
						  'confirmation' => $confirmation, 
						  'editportfolio' => 'Edit', 
						  'updateportfolio' => array(
						  	'id' => $portfolio -> getId(), 
						  	'title' => $portfolio -> getTitle(),
						  	'content' => $portfolio -> getContent(), 
						  	'image' => $portfolio -> getImage(), 
						  	'source' => $portfolio -> getSourceCode(), 
						  	'preview' => $portfolio -> getPreview(), 
						  	'released' => $portfolio -> getReleased(),
						  	'categoryid' => $portfolio -> getCategoryID(), 
							
						  )
					)
			  );
	}

	public function delete($id) {

		$portfolio = new Portfolio;
		$user = new User;
		$portfolio -> read($id);
		$portfolio -> delete();

		$confirmation = array();
		$confirmation['message'] = 'Item has been deleted!';

		return $this -> twig -> render('Admin/portfolio.html.twig', array('tags' => $portfolio -> getJsonTags($portfolio -> getPortfolioTags()), 'portfolioitems' => $portfolio -> getItems(), 'authors' => $user -> getUsers(), 'field_errors' => array(), 'confirmation' => $confirmation));

	}
	
	public function editCategory($id) {

		$portfolio = new PortfolioCategory;
		$user = new User;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();

		$portfolio -> read($id);

		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('name', 'slug'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('name', 'slug'));
		
			if($portfolio -> getSlug() != $fields['slug'] && $portfolio -> exists(array('slug' => $fields['slug']))) {
				$error['user'][] = 'Slug already exists!';
			}

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}
			
			if (empty($error)) {

				$portfolio -> setName(ucfirst($fields['name']));
				$portfolio -> setSlug($fields['slug']);

				$portfolio -> update();

				$confirmation['message'] = 'Category has been edited!';
			}
		}

		return $this -> twig -> render('Admin/portfoliocategories.html.twig', array('categories' => $portfolio -> getCategories(), 'field_errors' => $error, 'confirmation' => $confirmation, 'editcategory' => 'Edit', 'updatecategory' => array('id' => $portfolio -> getId(), 'name' => $portfolio -> getName(), 'slug' => $portfolio -> getSlug())));

	}

	public function deleteCategory($id) {

		$portfolio = new PortfolioCategory;
		$portfolio -> read($id);
		$portfolio -> delete();

		$confirmation = array();
		$confirmation['message'] = 'Category has been deleted!';

		return $this -> twig -> render('Admin/portfoliocategories.html.twig', array('categories' => $portfolio -> getCategories(), 'field_errors' => array(), 'confirmation' => $confirmation));

	}
	
	public function manageCategories() {

		$category = new PortfolioCategory;
		$form = new FormHandler;

		$error = array();
		$confirmation = array();

		if ($form -> isMethod('post')) {

			$fields = $form -> getFields(array('name', 'slug'));

			$validator = new FormValidator($fields);

			$validator -> rule('required', array('name', 'slug'));

			if ($category -> exists(array('slug' => $fields['slug']))) {
				$error['user'][] = 'Slug already exists!';
			}

			if (!$validator -> validate()) {
				$error = array_merge($error, $validator -> errors());
			}

			if (empty($error)) {

				$category -> setName(ucfirst($fields['name']));
				$category -> setSlug($fields['slug']);

				$category -> create();

				$confirmation['message'] = 'Category has been created!';

				$form -> unsetAll();
			}
		}

		return $this -> twig -> render('Admin/portfoliocategories.html.twig', array('categories' => $category -> getCategories(), 'field_errors' => $error, 'confirmation' => $confirmation));
	}
}
