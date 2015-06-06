<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Laasti\Route;

use League\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of BasicRoute
 *
 * @author Sonia
 */
class BasicRoute extends AbstractRoute
{

    protected $method;
    protected $uri;
    protected $controller;
    protected $rights;

    public function __construct($method, $uri, $controller, $rights = array())
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->controller = $controller;
        $this->rights = $rights;
    }
    
    public function boot(Request $request, ContainerInterface $container) {}
}
