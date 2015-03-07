<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Laasti\Route;

use Laasti\Stack\MiddlewareInterface;
use League\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of Route
 *
 * @author Sonia
 */
class RouteMiddleware implements MiddlewareInterface
{

    public function handle(Request $request, RouteCollection $routes = null)
    {
        //To be comptible with the interface we need to do the check manually because $routes can be null
        if (!$routes instanceof RouteCollection) {
            throw new \InvalidArgumentException("The route middleware requires League\Route\RouteCollection as a second parameter.");
        }

        $dispatcher = $routes->getDispatcher();

        $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        return $response;
    }

}
