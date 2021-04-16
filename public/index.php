<?php

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\BlogAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HomeAction;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\ProfilerMiddleware;

use Aura\Router\RouterContainer;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;

use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require "vendor/autoload.php";

//Init
$params = [
    'users' => ['admin' => 'admin'],
];

$aura = new RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', HomeAction::class);

$routes->get('cabinet', '/cabinet', [
    ProfilerMiddleware::class,
    new BasicAuthMiddleware($params['users']),
    CabinetAction::class,
]);

$routes->get('about', '/about', AboutAction::class);
$routes->get('blog', '/blog', BlogAction::class);
$routes->get('blog_show', '/blog/{id}', ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();

//Running
$request = ServerRequestFactory::fromGlobals();
try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $handlers = $result->getHandler();
    $pipeline = new Pipeline();
    foreach (is_array($handlers) ? $handlers : [$handlers] as $handler){
        $pipeline->pipe($resolver->resolve($handler));
    }
    $response = $pipeline($request, new NotFoundHandler());

} catch (RequestNotMatchedException $e) {
    $handler = new NotFoundHandler();
    $response = $handler();
}

//Postprocessing
$response = $response->withHeader('X-Developer', 'VLAD');

//Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
