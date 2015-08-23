<?php

namespace Laasti\Route;

use Laasti\Stack\Middleware\PrepareableInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Calls the controller previously set by the DefineControllerMiddleware
 *
 * Use ControllerDefinitionStrategy as the RouteCollection dispatcher.
 * Make sure that the DefineControllerMiddleware happens before
 * By default, _controllerDefinition will be define in the request attributes.
 */
class CallControllerMiddleware implements PrepareableInterface
{

    /**
     * @var string
     */
    protected $requestParameter;

    /**
     * Constructor
     * 
     * @param string $requestParameter Name used as the request attribute
     */
    public function __construct($requestParameter = '_controllerDefinition')
    {
        $this->requestParameter = $requestParameter;
    }

    /**
     * Middleware method, calls the controller using the ControllerDefinition
     * set by DefineControllerMiddleware
     *
     * @param Request $request
     * @return Request
     */
    public function prepare(Request $request)
    {

        $definition = $request->attributes->get($this->requestParameter);

        if (!$definition) {
            throw new RuntimeException('The controller could not be found. Check that the same parameter is used by DefineControllerMiddleware.');
        }

        return $definition->callInstance($request);
    }

}
