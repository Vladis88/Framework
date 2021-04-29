<?php

use Framework\Http\Middleware\ErrorHandler\ErrorHandlerMiddleware;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator;
use Infrastructure\Http\Middleware\ErrorHandler\HtmlErrorResponseGenerator;
use Framework\Http\Middleware\ErrorHandler\WhoopsErrorResponseGenerator;
use App\Http\Middleware\NotFoundHandler;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Framework\View\Twig\TwigRender;
use Interop\Container\ContainerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\RunInterface;
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
                    $container->get(NotFoundHandler::class)
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
                if ($container->get('config')['debug']) {
                    return new WhoopsErrorResponseGenerator($container->get(RunInterface::class),
                        new Response(),
                    );
                }
                return new HtmlErrorResponseGenerator($container->get(TwigRender::class),
                    new Response(),
                    [
                        '403' => 'error/403',
                        '404' => 'error/404',
                        'error' => 'error/error',
                    ]
                );
            },
            RunInterface::class => function () {
                $whoops = new Run();
                $whoops->writeToOutput(false);
                $whoops->allowQuit(false);
                $whoops->pushHandler(new PrettyPageHandler());
                $whoops->register();
                return $whoops;
            },
        ],
    ],

    'debug' => false,
];