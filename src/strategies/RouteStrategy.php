<?php

namespace Laasti\Route\Strategies;

use League\Route\Strategy\AbstractStrategy;
use League\Route\Strategy\StrategyInterface;
use Laasti\Route\RouteInterface;
use RuntimeException;

class RouteStrategy extends AbstractStrategy implements StrategyInterface
{
    protected $containerParameter;

    public function __construct($containerParameter = 'Laasti.ActiveRoute')
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
        $route = $this->getContainer()->get($controller[0]);

        call_user_func_array(array($route, $controller[1]), [
            $request, $this->getContainer()
        ]);
        
        $this->getContainer()->add($this->containerParameter, $route);

        if ($route instanceof RouteInterface) {
            return $request;
        }

        throw new RuntimeException(
            'When using the Route Strategy you must ' .
            'return an instance of [Laasti\Route\RouteInterface]'
        );
    }

}
