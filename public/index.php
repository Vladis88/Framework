<?php

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HomeAction;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\CredentialsMiddleware;
use App\Http\Middleware\ErrorHandlerMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\ProfilerMiddleware;

use Aura\Router\RouterContainer;

use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;

use Framework\Http\Router\Router;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require "vendor/autoload.php";

//Configure
$container = new Container();
$container->set('config', [
    'debug' => true,
    'users' => ['user' => 'user'],
]);

$container->set(Application::class, function (Container $container) {
    return new Application(
        $container->get(MiddlewareResolver::class),
        $container->get(Router::class),
        new NotFoundHandler(),
        new Response()
    );
});

$container->set(BasicAuthMiddleware::class, function (Container $container) {
    return new BasicAuthMiddleware($container->get('config')['users']);
});

$container->set(ErrorHandlerMiddleware::class, function (Container $container) {
    return new ErrorHandlerMiddleware($container->get('config')['debug']);
});

$container->set(MiddlewareResolver::class, function () {
    return new MiddlewareResolver();
});

$container->set(RouteMiddleware::class, function (Container $container) {
    return new RouteMiddleware($container->get(Router::class));
});

$container->set(DispatchMiddleware::class, function (Container $container) {
    return new DispatchMiddleware($container->get(MiddlewareResolver::class));
});

$container->set(Router::class, function () {
    return new AuraRouterAdapter(new RouterContainer());
});

//Init

/** @var Application $app */
$app = $container->get(Application::class);

$app->pipe($container->get(ErrorHandlerMiddleware::class));
$app->pipe(CredentialsMiddleware::class);
$app->pipe(ProfilerMiddleware::class);
$app->pipe($container->get(RouteMiddleware::class));
$app->pipe('cabinet', $container->get(BasicAuthMiddleware::class));
$app->pipe($container->get(DispatchMiddleware::class));

$app->get('home', '/', HomeAction::class);
$app->get('cabinet', '/cabinet', CabinetAction::class);
$app->get('about', '/about', AboutAction::class);
$app->get('blog', '/blog', IndexAction::class);
$app->get('blog_show', '/blog/{id}', ShowAction::class, ['tokens' => ['id' => '\d+']]);
//Running
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Response());

//Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
