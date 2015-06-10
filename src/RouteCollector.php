<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Laasti\Route;

use InvalidArgumentException;
use League\Container\ContainerInterface;
use League\Route\RouteCollection;

/**
 * Description of Router
 *
 * @author Sonia
 */
class RouteCollector
{

    protected $container;
    protected $routes;

    public function __construct(RouteCollection $routes, ContainerInterface $container)
    {
        $this->container = $container;
        $this->routes = $routes;
    }

    public function add($route)
    {

        if (is_string($route)) {
            $classname = $route;
            $route = $this->container->get($route);
            $this->container->add($classname, $route);
        } else {
            $classname = 'Router_' . $route->getMethod() . '' . $route->getUri();
            $this->container->add($classname, $route);
        }


        if ($route instanceof RouteInterface) {
            $this->routes->addRoute($route->getMethod(), $route->getUri(), $classname . '::boot');
            return $route;
        }

        throw new InvalidArgumentException('The route must be an instance of Laasti\\Route\\RouteInterface.');
    }

    public function create($method, $uri, $controller, $rights = [])
    {
        return $this->add(new BasicRoute($method, $uri, $controller, $rights));
    }

}
