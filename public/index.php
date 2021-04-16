<?php

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\BlogAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HomeAction;

use App\Http\Middleware\BasicAuthActionMiddleware;
use App\Http\Middleware\ProfilerMiddleware;
use Aura\Router\RouterContainer;
use Framework\Http\ActionResolver;

use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
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

$routes->get('cabinet', '/cabinet', function (ServerRequestInterface $request) use ($params) {
    $pipeline = new Pipeline();

    $pipeline->pipe(new ProfilerMiddleware());
    $pipeline->pipe(new BasicAuthActionMiddleware($params['users']));
    $pipeline->pipe(new CabinetAction());

    return $pipeline($request, function () {
        return new HtmlResponse('Undefined', 404);
    });
});

$routes->get('about', '/about', AboutAction::class);
$routes->get('blog', '/blog', BlogAction::class);
$routes->get('blog_show', '/blog/{id}', ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
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
    $response = new HtmlResponse('Undefined page', 404);
}

//Postprocessing
$response = $response->withHeader('X-Developer', 'VLAD');

//Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
