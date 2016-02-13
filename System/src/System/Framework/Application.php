<?php

/**
 * @author Jordi Kroon
 * @version 1.0
 * @copyright (c) Copyright 2013
 * @package Framework
 */

namespace System\Framework;

use System\Framework\Exception\ExceptionHandler;
use System\Framework\HTTP\Response;

class Application
{
    /** core of the application
     *
     * @param bool $debug
     * @return string $execute output of pages
     * @throws \ErrorException
     * @throws \Exception
     */
    final public static function run($debug = false)
    {
        try {
            $maincontroller = new MainController;

            return $maincontroller->execute();
        } catch (\Exception $e) {

            $exception = new ExceptionHandler($e);
            $exception->save('Exceptions.log');


            if ($debug === true) {
                return $exception->getContent();
            } else {
                $response = new Response;
                $response->redirect(500);
            }
        }
    }

}
