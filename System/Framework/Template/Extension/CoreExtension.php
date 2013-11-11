<?php

namespace System\Framework\Template\Extension;

use System\Framework\HTTP\Response;

class CoreExtension extends \Twig_Extension {

    public function getFilters()
    {
        return array(
            
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('url', function($routeName) {
				$response = new Response;
            	return $response -> url($routeName);
            }),
        );
    }
    public function getName()
    {
        return 'project';
    }
}
