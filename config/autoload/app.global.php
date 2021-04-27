<?php

use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\ErrorHandlerMiddleware;
use App\Http\Middleware\NotFoundHandler;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Framework\View\PhpViewRender;
use Interop\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'dependencies' => [
        'abstract_factories' => [
            ReflectionBasedAbstractFactory::class,
        ],
        'factories' => [
            Application::class => function (ContainerInterface $container) {
                return new Application(
                    $container->get(MiddlewareResolver::class),
                    $container->get(Router::class),
                    $container->get(NotFoundHandler::class),
                    new Response()
                );
            },
            Router::class => function () {
                return new AuraRouterAdapter(new RouterContainer());
            },
            MiddlewareResolver::class => function (ContainerInterface $container) {
                return new MiddlewareResolver($container);
            },
            BasicAuthMiddleware::class => function (ContainerInterface $container) {
                return new BasicAuthMiddleware($container->get('config')['users']);
            },
            ErrorHandlerMiddleware::class => function (ContainerInterface $container) {
                return new ErrorHandlerMiddleware(
                    $container->get('config')['debug'],
                    $container->get(PhpViewRender::class));
            },
            PhpViewRender::class => function (ContainerInterface $container) {
                return new PhpViewRender('views', $container->get(Router::class));
            },
        ],
    ],

    'debug' => false,
];