<?php

namespace Laasti\Route\Test;

use Laasti\Route\ControllerDefinitionStrategy;
use Laasti\Route\DefineControllerMiddleware;
use League\Container\Container;
use League\Route\Http\Exception\NotFoundException;
use League\Route\RouteCollection;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefineControllerMiddlewareTest extends PHPUnit_Framework_TestCase
{

    public function testMiddlewareInterface()
    {
        $routes = new RouteCollection;
        $define_middleware = new DefineControllerMiddleware($routes);

        $this->assertInstanceOf('Laasti\Stack\MiddlewareInterface', $define_middleware);
    }
    
    public function testEmptyRouteCollection()
    {
        $routes = new RouteCollection;
        $route = new DefineControllerMiddleware($routes);
        
        try {
            $route->handle(new Request);
        } catch(NotFoundException $e) {
            $this->assertInstanceOf('League\Route\Http\Exception\NotFoundException', $e);
            return;
        }
        $this->fail();
    }

    public function testFoundRouteCollection()
    {
        $container = new Container();

        $fakeController = $this->getMockBuilder('FakeController')
                ->setMethods(array('display'))
                ->getMock();
        $fakeController->expects($this->any())->method('display')->will($this->returnValue(new Response));
        $container->add('Controller', $fakeController);
        $routes = new RouteCollection($container);
        $routes->setStrategy(new ControllerDefinitionStrategy);
        $middleware = new DefineControllerMiddleware($routes);
        $routes->get('test/{name}', 'Controller::display');

        $request = $middleware->handle(Request::create('test/george'));
        $definition = $request->attributes->get('_controllerDefinition');

        $this->assertInstanceOf('Laasti\Route\ControllerDefinition', $definition);
        $this->assertInstanceOf('FakeController', $definition->getInstance());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $request);
        $this->assertEquals('george', $definition->getArguments()['name']);
    }

    public function testCustomRequestParameter()
    {
        $container = new Container();

        $fakeController = $this->getMockBuilder('FakeController')
                ->setMethods(array('display'))
                ->getMock();
        $fakeController->expects($this->any())->method('display')->will($this->returnValue(new Response));
        $container->add('Controller', $fakeController);
        $routes = new RouteCollection($container);
        $routes->setStrategy(new ControllerDefinitionStrategy);
        $middleware = new DefineControllerMiddleware($routes, 'customRequestParameter');
        $routes->get('test/{name}', 'Controller::display');

        $request = $middleware->handle(Request::create('test/george'));
        $definition = $request->attributes->get('customRequestParameter');

        $this->assertInstanceOf('Laasti\Route\ControllerDefinition', $definition);
        $this->assertInstanceOf('FakeController', $definition->getInstance());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $request);
        $this->assertEquals('george', $definition->getArguments()['name']);

    }

}
