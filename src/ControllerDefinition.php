<?php

namespace Laasti\Route;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Immutable controller definition stored in request
 */
class ControllerDefinition
{
    /**
     * Active controller instance
     * @var mixed
     */
    protected $instance;

    /**
     * Name of the controller method to call
     * @var string
     */
    protected $method;

    /**
     * Array of attributes from the routing
     * @var array
     */
    protected $arguments;

    /**
     * Constructor
     *
     * @param mixed $instance Controller instance
     * @param string $method Method to call
     * @param array $arguments Routing attributes
     */
    public function __construct($instance, $method, array $arguments = [])
    {
        $this->instance = $instance;
        $this->method = $method;
        $this->arguments = $arguments;

        if (!is_object($instance)) {
            throw new RuntimeException('"' . $instance . '" is not a PHP object and cannot be used as a controller.');
        }
        
        if (!method_exists($instance, $method)) {
            throw new RuntimeException('"' . get_class($instance) . '" does not have a method "'.$method.'".');
        }
    }

    /**
     * Calls the controller method
     *
     * @param mixed Any arguments will be passed along to the method else, the arguments are passed
     * @return Response
     */
    public function callController()
    {
        $args = func_get_args();

        if (empty($args)) {
            $args = $this->getArguments();
        }

        return call_user_func_array([$this->getInstance(), $this->getMethod()], $args);
    }

    /**
     * Returns Controller instance
     * @return mixed
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Returns the method to be called on the controller
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns an array containing the attributes from the routing
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

}
