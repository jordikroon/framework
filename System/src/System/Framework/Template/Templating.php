<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */

namespace System\Framework\Template;

use Cocur\Slugify\Bridge\Twig\SlugifyExtension;
use Cocur\Slugify\Slugify;

class Templating {

	private $cacheDir;
	private $viewDir;

	public function __construct() {
		\Twig_Autoloader::register();
	}

	public function setViewDir($directory) {
		$this -> viewDir = $directory;
	}

	public function getViewDir() {
		return $this -> viewDir;
	}

	public function setCacheDir($cache) {
		$this -> cacheDir = $cache;
	}

	public function getCacheDir() {
		return $this -> cacheDir;
	}

	public function getParser() {
		if (empty($this -> viewDir)) {
			throw new InvalidArgumentException('View directory not set. This is required!');
		} else {
			$loader = new \Twig_Loader_Filesystem($this -> viewDir);

			if (empty($this -> cacheDir)) {
				$twig = new TwigLoader($loader);
			} else {
				$twig = new TwigLoader($loader, array('cache' => $this -> cacheDir, 'auto_reload' => true));
			}
		
			$twig -> addExtension(new Extension\CoreExtension());
			$twig->addExtension(new SlugifyExtension(Slugify::create()));
			
			return $twig;
		}
	}
	
	


}
