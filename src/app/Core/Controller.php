<?php

namespace Organo\Core;


use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


abstract class Controller implements ControllerProviderInterface
{

    protected $routes = [];

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        foreach ($this->routes as $httpMethod => $routes) {
            foreach ($routes as $route => $action) {
                $app->$httpMethod($route, function (Request $request) use($action) {
                    return $this->$action($app, $request);
                });
            }
        }

        return $controllers;
    }

}