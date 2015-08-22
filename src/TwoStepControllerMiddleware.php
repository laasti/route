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
class TwoStepControllerMiddleware implements MiddlewareInterface
{

    protected $container;
    protected $containerParameter;

    public function __construct(ContainerInterface $container, $containerParameter = '_controller')
    {
        $this->container = $container;
        $this->containerParameter = $containerParameter;
    }

    public function handle(Request $request)
    {

        $controller = $request->attributes->get($this->containerParameter);
        $method = $request->attributes->get($this->containerParameter.'.method');
        
        if (!is_object($controller)) {
            throw new RuntimeException('The controller could not be found. Check that RouteMiddleware is before TwoStepControllerMiddleware.');
        }

        if (!method_exists($controller, $method)) {
            throw new RuntimeException('The method "'.$method.'" does not exists in the controller "'.get_class($controller).'".');
        }

        return call_user_func_array([$controller, $method], [$request]);
    }

}
