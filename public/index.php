<?php

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\BlogAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\HomeAction;

use Framework\Http\ActionResolver;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;

use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;

use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require "vendor/autoload.php";

//Init
$routes = new RouteCollection();

$routes->get('home', '/', HomeAction::class);
$routes->get('about', '/about', AboutAction::class);
$routes->get('blog', '/blog', BlogAction::class);
$routes->get('blog_show', '/blog/{id}', ShowAction::class, ['id' => '\d+']);

$router = new Router($routes);
$resolver = new ActionResolver();

//Running
$request = ServerRequestFactory::fromGlobals();
try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    /** callable action */
    $action = $resolver->resolve($result->getHandler());
    $response = $action($request);

} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Undefined page'], 404);
}

//Postprocessing
$response = $response->withHeader('X-Developer', 'VLAD');

//Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
