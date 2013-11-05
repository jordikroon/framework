<?php

namespace System\Framework\Template;

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
		if(empty($this -> viewDir)) {
			throw new InvalidArgumentException('View directory not set. This is required!');
		} else {
			$loader = new \Twig_Loader_Filesystem($this -> viewDir);
			
			if(empty($this -> cacheDir)) {
				return new \Twig_Environment($loader);					
			} else {
				return new \Twig_Environment($loader, array('cache' => $this -> cacheDir, 'auto_reload' => true));					
			}
		}
	}
}
