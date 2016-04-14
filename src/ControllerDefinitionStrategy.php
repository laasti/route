<?php

namespace Laasti\Route;

use Laasti\Route\ControllerDefinition;
use League\Route\Strategy\AbstractStrategy;
use League\Route\Strategy\StrategyInterface;
use RuntimeException;

class ControllerDefinitionStrategy extends AbstractStrategy implements StrategyInterface
{

    /**
     * Finds the controller in the container and returns a ControllerDefinition
     * 
     * @param array $route Controller class and method
     * @param array $vars Attributes extracted from URL
     *
     * @throws RuntimeException
     * @return ControllerDefinition
     */
    public function dispatch($route, array $vars)
    {
        list($controller, $method) = $route;

        $di = $this->getContainer();
        return new ControllerDefinition(function() use ($di, $controller) {
            //Attempt to retrieve controller from container
            return $di->get($controller);
        }, $method, $vars);
    }

}
