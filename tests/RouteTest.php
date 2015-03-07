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
        $route = new RouteMiddleware();

        $this->assertTrue($route instanceof MiddlewareInterface);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMiddlewareNoParam()
    {
        $route = new RouteMiddleware();

        $route->handle(new Request);
    }

    /**
     * @expectedException \League\Route\Http\Exception\NotFoundException
     */
    public function testMiddlewareEmptyRouteCollection()
    {
        $route = new RouteMiddleware();

        $route->handle(new Request, new RouteCollection);
    }

    public function testMiddlewareFoundRouteCollection()
    {
        $route = new RouteMiddleware();
        $router = new RouteCollection;
        $router->get('test', function() { return new \Symfony\Component\HttpFoundation\Response;});

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $route->handle(Request::create('test'), $router));
    }

}
