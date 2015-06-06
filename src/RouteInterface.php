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
 *
 * @author Sonia
 */
interface RouteInterface
{
    public function boot(Request $request, ContainerInterface $container);
    public function getController();
    public function getMethod();
    public function getRights();
    public function getUri();
}
