<?php

namespace System\Framework\Template\Extension;

class AsseticExtension extends \Twig_Extension {

    public function getFilters()
    {
        return array();
    }

    public function getFunctions()
    {	
        return array();
    }
    public function getName()
    {
        return 'assetic';
    }
}
