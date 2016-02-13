<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2016
 * @package Framework
 */

namespace System\Framework\Routing;

Class Router
{
    /**
     * @var Route[]
     */
    private $routes = [];

    /** adds a route
     *
     * @param object $route route object
     */
    public function setRoute($route)
    {
        $this->routes[] = $route;
    }

    /** returns the matched route
     *
     * @param string $request request URI
     *
     * @return object $route matched route
     */
    public function getRoute($request)
    {
        foreach ($this->routes as $route) {
            if ($data = $route->match($request)) {

                foreach ($data as $key => $param) {
                    if (is_string($key)) {
                        $route->addParam($data[$key]);
                    }
                }

                return $route;
            }
        }

        return null;
    }
}
