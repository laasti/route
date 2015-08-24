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

        $this->assertInstanceOf('Laasti\Stack\Middleware\PrepareableInterface', $call_middleware);
    }

    public function testNotDefinedController()
    {
        $middleware = new CallControllerMiddleware();
        try {
            $middleware->prepare(new Request);
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
                                ->setMethods(['callController'])
                                ->getMock();
        $request = new Request;

        $controllerDefinition->expects($this->once())->method('callController')->with($request)->will($this->returnValue(new Response));
        $request->attributes->set('_controllerDefinition', $controllerDefinition);
        $response = $middleware->prepare($request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    public function testCustomRequestParameter()
    {
        $middleware = new CallControllerMiddleware('customParameter');
        $controllerDefinition = $this->getMockBuilder('Laasti\Route\ControllerDefinition')
                                ->disableOriginalConstructor()
                                ->setMethods(['callController'])
                                ->getMock();
        $request = new Request;

        $controllerDefinition->expects($this->once())->method('callController')->with($request)->will($this->returnValue(new Response));
        $request->attributes->set('customParameter', $controllerDefinition);
        $response = $middleware->prepare($request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }
}
