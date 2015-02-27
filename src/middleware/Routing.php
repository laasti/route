<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Laasti\Route\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of Environment
 *
 * @author Sonia
 */
class Routing implements \Laasti\Services\MiddlewareInterface
{

    public function handle(Request $request, \Laasti\Services\RouteCollectionInterface $routes = null)
    {

        //TODO maybe better to get router directly, but would have to make it a singleton, maybe use an alias
        $dispatcher = $routes->getDispatcher();

        $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        return $response;
    }

    public function terminate(Request $request, Response $response) {
        
    }

}
