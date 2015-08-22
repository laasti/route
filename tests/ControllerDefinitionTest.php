<?php

namespace Laasti\Route\Test;

use Laasti\Route\ControllerDefinition;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

class ControllerDefinitionTest extends \PHPUnit_Framework_TestCase
{

    public function testInvalidInstance()
    {
        try {
            new ControllerDefinition('test', 'test');
        } catch (RuntimeException $e) {
            $this->assertInstanceOf('RuntimeException', $e);
            return;
        }

        $this->fail();
    }

    public function testUndefinedMethod()
    {
        try {
            $mock = $this->getMockBuilder('FakeController')->getMock();
            new ControllerDefinition($mock, 'test');
        } catch (RuntimeException $e) {
            $this->assertInstanceOf('RuntimeException', $e);
            return;
        }

        $this->fail();
    }

    public function testInvalidArguments()
    {
        try {
            $mock = $this->getMockBuilder('FakeController')->setMethods(['test'])->getMock();
            new ControllerDefinition($mock, 'test', 'BOO!');
        } catch (RuntimeException $e) {
            $this->assertInstanceOf('RuntimeException', $e);
            return;
        }

        $this->fail();
    }

    public function testInstantiation()
    {
        $mock = $this->getMockBuilder('FakeController')->setMethods(['test'])->getMock();
        $arguments = ['test' => true];
        $definition = new ControllerDefinition($mock, 'test', $arguments);

        $this->assertInstanceOf('FakeController', $definition->getInstance());
        $this->assertEquals('test', $definition->getMethod());
        $this->assertEquals($arguments['test'], $definition->getArguments()['test']);
    }

    public function testcallController()
    {
        $mock = $this->getMockBuilder('FakeController')->setMethods(['test'])->getMock();
        $mock->expects($this->at(0))->method('test')->will($this->returnValue('My Value'));
        $mock->expects($this->at(1))->method('test')->will($this->returnArgument(0));
        $mock->expects($this->at(2))->method('test')->will($this->returnArgument(0));
        $definition = new ControllerDefinition($mock, 'test', ['test' => true]);

        $this->assertEquals('My Value', $definition->callController());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $definition->callController(new Request));
        $this->assertTrue($definition->callController());
    }

}
