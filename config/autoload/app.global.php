<?php

use App\Http\Middleware\ErrorHandler\ErrorHandlerMiddleware;
use App\Http\Middleware\ErrorHandler\ErrorResponseGenerator;
use App\Http\Middleware\ErrorHandler\HtmlErrorResponseGenerator;
use App\Http\Middleware\NotFoundHandler;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Framework\View\Twig\TwigRender;
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
                return new MiddlewareResolver($container, new Response());
            },
            ErrorHandlerMiddleware::class => function (ContainerInterface $container) {
                return new ErrorHandlerMiddleware(
                    $container->get(ErrorResponseGenerator::class)
                );
            },
            ErrorResponseGenerator::class => function (ContainerInterface $container) {
                return new HtmlErrorResponseGenerator(
                    $container->get('config')['debug'],
                    $container->get(TwigRender::class)
                );
            }
        ],
    ],

    'debug' => false,
];