> This is an alpha version.

# Laasti/Route

An advanced implementation of PHP League Route package. A route middleware for Laasti/Stack.

Instead of mapping directly to your controller, a middle step is added: a route object is used to configure the current route.
This middle step can be used to check user's rights to access a route through an Authorization middleware.

```
   $container = new League\Container;
   $routes = new League\RouteCollection($container);
   $router = new Laasti\RouteCollector('/', $routes, $container);
   //Creates a BasicRoute
   $router->create('GET', '/welcome', 'WelcomeController::display', ['WELCOME', 'MEMBER']);
   //Equals to
   $router->add(new Laasti\Route\BasicRoute('GET', '/welcome', 'WelcomeController::display', ['WELCOME', 'MEMBER']));
   //You can also use the container
   $container->add('UserRoute', new Laasti\Route\BasicRoute('GET', '/welcome', 'WelcomeController::display', ['WELCOME', 'MEMBER']));
   $router->add('UserRoute');
```

Uses the excellent package from The PHP League: [League/Route](https://github.com/thephpleague/route).
