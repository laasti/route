<?php

namespace Laasti\Route\Test;

use Laasti\Route\CallControllerMiddleware;
use PHPUnit_Framework_TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CallControllerMiddlewareTest extends PHPUnit_Framework_TestCase
{

    public function testMiddlewareInterface()
    {
        $call_middleware = new CallControllerMiddleware();

        $this->assertInstanceOf('Laasti\Stack\MiddlewareInterface', $call_middleware);
    }

    public function testNotDefinedController()
    {
        $middleware = new CallControllerMiddleware();
        try {
            $middleware->handle(new Request);
        } catch(RuntimeException $e) {
            $this->assertInstanceOf('RuntimeException', $e);
            return;
        }
        $this->fail();
    }

    public function testController()
    {
        $middleware = new CallControllerMiddleware();
        $controllerDefinition = $this->getMockBuilder('Laasti\Route\ControllerDefinition')
                                ->disableOriginalConstructor()
                                ->setMethods(['callInstance'])
                                ->getMock();
        $request = new Request;

        $controllerDefinition->expects($this->once())->method('callInstance')->with($request)->will($this->returnValue(new Response));
        $request->attributes->set('_controllerDefinition', $controllerDefinition);
        $response = $middleware->handle($request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    public function testCustomRequestParameter()
    {
        $middleware = new CallControllerMiddleware('customParameter');
        $controllerDefinition = $this->getMockBuilder('Laasti\Route\ControllerDefinition')
                                ->disableOriginalConstructor()
                                ->setMethods(['callInstance'])
                                ->getMock();
        $request = new Request;

        $controllerDefinition->expects($this->once())->method('callInstance')->with($request)->will($this->returnValue(new Response));
        $request->attributes->set('customParameter', $controllerDefinition);
        $response = $middleware->handle($request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }
}
