<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package CMS
 */

namespace Application\Controller\Home;

use System\Framework\MainController;
use Application\Model\Settings;

use System\Framework\Config;

Class GithubController extends MainController
{

    public function index()
    {
        $content = [];

        $content['title'] = 'Github repositories';

        $cache = new \Github\HttpClient\CachedHttpClient();
        $cache->setCache(new \Github\HttpClient\Cache\FilesystemCache(__dir__ . '/../../../../../Cache/github'));

        $client = new \Github\Client($cache);

        $config = new Config;
        $config->loadFile(__dir__ . '/../../../../../Config/application.php');
        $github = $config->get('github');

        $content['repositories'] = $client->api('user')->repositories($github['username'], 'updated');

        return $this->twig->render('Core/github.html.twig', array('content' => $content));
    }

}
