<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Laasti\Route\Middlewares;

use Laasti\Stack\MiddlewareInterface;
use Laasti\Route\RouteInterface;
use League\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of Route
 *
 * @author Sonia
 */
class ControllerMiddleware implements MiddlewareInterface
{

    protected $container;
    protected $containerParameter;

    public function __construct(ContainerInterface $container, $containerParameter = 'Laasti.ActiveRoute')
    {
        $this->container = $container;
        $this->containerParameter = $containerParameter;
    }

    public function handle(Request $request)
    {
        //TODO What of automatic dependency resolution here, it feels weird
        if ($this->containerParameter instanceof RouteInterface) {
            $controller = $this->containerParameter->getController();
        } else {
            $controller = $this->container->get($this->containerParameter)->getController();
        }

        if (!strpos($controller, '::')) {
            throw new RuntimeException('The route CONTROLLER must be of the following format: Classname::method.');
        }

        $callable = explode('::', $controller);
        $callable[0] = $this->container->get($callable[0]);

        if (!is_object($callable[0])) {
            throw new \RuntimeException('The controller class could not be found through the container: '.$controller);
        }

        return call_user_func_array($callable, [$request]);
    }

}
