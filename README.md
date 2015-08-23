# Laasti/Route

An advanced implementation of PHP League Route package. A two-step route middleware for Laasti/Stack.

Instead of mapping directly to your controller, a middle step is added: a route object is used to configure the current route.
This middle step can be used to check user's rights to access a route through an Authorization middleware.

Uses the excellent package from The PHP League: [League/Route](https://github.com/thephpleague/route).

## Installation

```
composer require laasti/route
```

## Usage

Currently does not support closures nor functions as routes, only objects.

With Laasti\Stack:

```php
   $container = new League\Container;
   $routes = new League\Route\RouteCollection($container);
   $container->add('Laasti\Route\DefineControllerMiddleware')->withArgument($routes);
   $routes->setStrategy(new Laasti\Route\ControllerDefinitionStrategy);
   $request = Symfony\Component\HttpFoundation\Request::create('/test');
   $routes->get('/test', 'MyController::display');
   $resolver = new Laasti\Stack\ContainerResolver($container);
   $stack = new Laasti\Stack\Stack($resolver);
   $stack->push('Laasti\Route\DefineControllerMiddleware');
   $stack->push('Laasti\Route\CallControllerMiddleware');

   $stack->execute($request);

```

Without Laasti\Stack:

```php
   $container = new League\Container;
   $routes = new League\Route\RouteCollection($container);
   $routes->setStrategy(new Laasti\Route\ControllerDefinitionStrategy);
   $request = Symfony\Component\HttpFoundation\Request::create('/test/George');
   $routes->get('/test/{name}', 'MyController::display');

   $definition = $routes->getDispatcher()->dispatch($request->getMethod(), $request->getPathInfo());

   //Pass the request or any arguments to the controller
   //Calls MyController->display($request);
   $request->attributes->add($definition->getArguments());
   echo $definition->callController($request);

   //Or, uses route attributes as arguments
   //Calls MyController->display($name);
   echo $definition->callController();
```

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History

See CHANGELOG.md for more information.

## Credits

Author: Sonia Marquette (@nebulousGirl)

## License

Released under the MIT License. See LICENSE.txt file.



