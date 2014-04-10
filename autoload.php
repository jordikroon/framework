<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 */
class ClassLoader {

	private $fileExtension = '.php';

	private $namespace;

	private $includePath;

	private $namespaceSeparator = '\\';

	public function __construct($namespace = null, $includePath = null) {
		$this -> namespace = $namespace;
		$this -> includePath = $includePath;
	}

	public function setNamespaceSeparator($sep) {
		$this -> namespaceSeparator = $sep;
	}

	public function setIncludePath($includePath) {
		$this -> includePath = $includePath;
	}

	public function getNamespaceSeparator() {
		return $this -> namespaceSeparator;
	}

	public function getIncludePath() {
		return $this -> includePath;
	}

	public function setFileExtension($fileExtension) {
		$this -> fileExtension = $fileExtension;
	}

	public function getFileExtension() {
		return $this -> fileExtension;
	}

	public function register() {
		spl_autoload_register(array($this, 'loadClass'));
	}

	public function unregister() {
		spl_autoload_unregister(array($this, 'loadClass'));
	}

	public function loadClass($className) {
		$fileName = '';

		
		if ($className == $this -> namespace || $this -> includePath != null) {
			if (false !== ($lastNsPos = strripos($className, $this -> namespaceSeparator))) {
				$namespace = substr($className, 0, $lastNsPos);
				$className = substr($className, $lastNsPos + 1);
				$fileName = str_replace($this -> namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
			}

			$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $this -> fileExtension;

			$check = explode(DIRECTORY_SEPARATOR, $fileName);
			
			if($this -> includePath != null) {
				if($check[0] == $this -> namespace) {
					require_once ($this -> includePath !== null ? $this -> includePath . DIRECTORY_SEPARATOR : '') . $fileName;
				}
			} else {
				require_once ($this -> includePath !== null ? $this -> includePath . DIRECTORY_SEPARATOR : '') . $fileName;
			}
		}
	}

}
