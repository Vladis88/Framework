<?php

use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\ErrorHandlerMiddleware;
use App\Http\Middleware\NotFoundHandler;
use Aura\Router\RouterContainer;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Zend\Diactoros\Response;

/** @var Container $container */
return [
    Application::class => function (Container $container) {
        return new Application(
            $container->get(MiddlewareResolver::class),
            $container->get(Router::class),
            new NotFoundHandler(),
            new Response()
        );
    },
    Router::class => function () {
        return new AuraRouterAdapter(new RouterContainer());
    },
    MiddlewareResolver::class => function (Container $container) {
        return new MiddlewareResolver($container);
    },
    BasicAuthMiddleware::class => function (Container $container) {
        return new BasicAuthMiddleware($container->get('config')['users']);
    },
    ErrorHandlerMiddleware::class => function (Container $container) {
        return new ErrorHandlerMiddleware($container->get('config')['debug']);
    },
];
