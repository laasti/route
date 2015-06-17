<?php

namespace Laasti\Route\Strategies;

use League\Route\Strategy\AbstractStrategy;
use League\Route\Strategy\StrategyInterface;
use RuntimeException;

class TwoStepControllerStrategy extends AbstractStrategy implements StrategyInterface
{
    protected $containerParameter;

    public function __construct($containerParameter = '_controller')
    {
        $this->containerParameter = $containerParameter;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($controller, array $vars)
    {
        //Prepare Request to be dispatched
        //TODO Move to PSR7 implementation instead
        $request = $this->getContainer()->get('Symfony\Component\HttpFoundation\Request');
        $request->attributes->add($vars);
        $instance = $this->getContainer()->get($controller[0]);

        if (!is_object($instance)) {
            throw new RuntimeException('The controller "'.$controller[0].'" was not found in the container.');
        }
        
        $request->attributes->set($this->containerParameter, $instance);
        $request->attributes->set($this->containerParameter.'.method', $controller[1]);

        return $request;
    }

}
