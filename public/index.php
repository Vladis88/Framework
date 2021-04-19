<?php

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\BlogAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HomeAction;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\CredentialsMiddleware;
use App\Http\Middleware\ErrorHandlerMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\ProfilerMiddleware;

use Aura\Router\RouterContainer;

use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;

use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require "vendor/autoload.php";

//Init
$params = [
    'debug' => true,
    'users' => ['admin' => 'admin'],
];

$aura = new RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', HomeAction::class);

$routes->get('cabinet', '/cabinet', [
    new BasicAuthMiddleware($params['users']),
    CabinetAction::class,
]);

$routes->get('about', '/about', AboutAction::class);
$routes->get('blog', '/blog', BlogAction::class);
$routes->get('blog_show', '/blog/{id}', ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();
$app = new Application($resolver, new NotFoundHandler());


$app->pipe(new ErrorHandlerMiddleware($params['debug']));
$app->pipe(CredentialsMiddleware::class);
$app->pipe(ProfilerMiddleware::class);

//Running
$request = ServerRequestFactory::fromGlobals();
try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $handler = $result->getHandler();
    $app->pipe($resolver->resolve($handler));
} catch (RequestNotMatchedException $e) {
}

try {
    $response = $app->run($request);
} catch (\Throwable $e) {
    $response = new JsonResponse([
        'error' => 'Server error!',
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
    ], 500);
}


//Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
