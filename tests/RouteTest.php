<?php

namespace Laasti\Stack\Test;

use Laasti\Route\RouteMiddleware;
use Laasti\Stack\MiddlewareInterface;
use League\Route\RouteCollection;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;

class RouteTest extends PHPUnit_Framework_TestCase
{

    public function testMiddlewareInterface()
    {
        $router = new RouteCollection;
        $route = new RouteMiddleware($router);

        $this->assertTrue($route instanceof MiddlewareInterface);
    }

    /**
     * @expectedException \League\Route\Http\Exception\NotFoundException
     */
    public function testMiddlewareEmptyRouteCollection()
    {
        $router = new RouteCollection;
        $route = new RouteMiddleware($router);

        $route->handle(new Request);
    }

    public function testMiddlewareFoundRouteCollection()
    {
        $router = new RouteCollection;
        $route = new RouteMiddleware($router);
        $router->get('test', function() { return new \Symfony\Component\HttpFoundation\Response;});

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $route->handle(Request::create('test')));
    }

}
