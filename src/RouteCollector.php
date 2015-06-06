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
    protected $mount;

    public function __construct($mount, RouteCollection $routes, ContainerInterface $container)
    {
        $this->mount = $mount;
        $this->container = $container;
        $this->routes = $routes;
    }

    public function setMount($mount)
    {
        $this->mount = $mount;
        return $this;
    }

    public function add($route, $mount = null)
    {
        $mount = is_null($mount) ? $this->mount : $mount;

        if (is_string($route)) {
            $classname = $route;
            $route = $this->container->get($route);
            $this->container->add($classname, $route);
        } else {
            $classname = 'Router_' . $route->getMethod() . '' . $mount . $route->getUri();
            $this->container->add($classname, $route);
        }


        if ($route instanceof RouteInterface) {
            $this->routes->addRoute($route->getMethod(), $mount . $route->getUri(), $classname . '::boot');
            return $route;
        }

        throw new InvalidArgumentException('The route must be an instance of Laasti\\Route\\RouteInterface.');
    }

    public function create($method, $uri, $controller, $rights = [])
    {
        return $this->add(new BasicRoute($method, $uri, $controller, $rights));
    }

}
