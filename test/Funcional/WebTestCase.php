<?php

namespace Organo\Test\Funcional;


use Silex\WebTestCase as SilexWebTestCase;


class WebTestCase extends SilexWebTestCase
{

    public function createApplication()
    {
        $app = require __DIR__ . "/../../src/app/bootstrap.php";
        $app['debug'] = true;
        unset($app['exception_handler']);

        return $app;
    }

}