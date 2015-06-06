<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Laasti\Route;

/**
 *
 * @author Sonia
 */
abstract class AbstractRoute implements RouteInterface
{
    protected $controller;
    protected $method;
    protected $uri;
    protected $rights;
    
    public function getController() {
        return is_null($this->controller) ? static::CONTROLLER : $this->controller;
    }
    public function getMethod() {
        return is_null($this->method) ? static::METHOD : $this->method;
    }
    public function getUri() {
        return is_null($this->uri) ? static::URI : $this->uri;
    }
    public function getRights() {
        return $this->rights;
    }
}
