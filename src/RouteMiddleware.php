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
    protected $routes; 
    
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function handle(Request $request)
    {
        $dispatcher = $this->routes->getDispatcher();

        $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        return $response;
    }

}
