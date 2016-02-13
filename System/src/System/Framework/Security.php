<?php

namespace System\Framework;

use System\Framework\Routing\Route;
use System\Framework\HTTP\Response;
use Kunststube\CSRFP\SignatureGenerator;
use System\Framework\Storage\Session;

class Security
{
    const PERMISSION_REQUESTED = 0;
    const PERMISSION_DENIED = 2;
    const PERMISSION_GRANTED = 1;

    /**
     * @var Config
     */
    private $config;

    public function __construct()
    {
        $this->config = new Config;
        $this->config->loadFile(__dir__ . '/../../Config/security.php');
    }

    /**
     * @param $routeName
     * @return int
     * @throws \ErrorException
     */
    public function isAuthorized($routeName)
    {
        $route = new Route;

        $security = $this->config->get('security');

        foreach ($security['securedroutes'] AS $key => $roles) {
            $routeInfo = $route->getByName($key);
            $securityRoles = $this->config->get('roles');

            if ($routeInfo[2] !== $routeName) {
                continue;
            }

            $data = $route->parseRouteData($this->config->get('checklogin'));
            $controllerClass = '\\Application\\Controller\\' . $data[0] . '\\' . $data[1] . 'Controller';

            if (!class_exists($controllerClass) || !method_exists($controllerClass, $data[2])) {
                throw new \ErrorException(sprintf('LoginRoute "%s" does not exists', $security['checklogin']));
            }

            $controller = new $controllerClass;
            $method = $data[2];

            if (count($securityRoles) !== count(array_unique($securityRoles))) {
                throw new \LogicException('We detected duplicated role values in your security config file, please fix this issue!');
            }

            $response = $controller->$method();

            if (!in_array(array_search($response[0], $securityRoles), $roles)) {
                return $response[1] === true ? self::PERMISSION_DENIED : self::PERMISSION_REQUESTED;
            }
        }

        return self::PERMISSION_GRANTED;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function checkSignature($token)
    {
        $session = new Session;
        $signer = new SignatureGenerator($session->get('csrfhash'));

        return ($signer->validateSignature($token) ? true : false);
    }

    /**
     * @return string
     */
    public function generateSignature()
    {
        $session = new Session;
        $session->create('csrfhash', $this->config->get('csrfsecret') . uniqid(), true);
        $signer = new SignatureGenerator($session->get('csrfhash'));

        return htmlspecialchars($signer->getSignature());
    }
}
