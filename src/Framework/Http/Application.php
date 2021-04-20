<?php


namespace Framework\Http;


use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\RouteData;
use Framework\Http\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

class Application extends MiddlewarePipe
{
    private MiddlewareResolver $resolver;
    private $default;
    private Router $router;

    /**
     * Application constructor.
     * @param \Framework\Http\Pipeline\MiddlewareResolver $resolver
     * @param \Framework\Http\Router\Router $router
     * @param callable $default
     * @param \Psr\Http\Message\ResponseInterface $responsePrototype
     */
    public function __construct(MiddlewareResolver $resolver, Router $router, callable $default, ResponseInterface $responsePrototype)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->setResponsePrototype($responsePrototype);
        $this->default = $default;
        $this->router = $router;
    }

    public function pipe($path, $middleware = null): MiddlewarePipe
    {
        if ($middleware === null) {
            return parent::pipe($this->resolver->resolve($path, $this->responsePrototype));
        }
        return parent::pipe($path, $this->resolver->resolve($middleware, $this->responsePrototype));

    }


    public function run(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this($request, $response, $this->default);
    }

    public function route($name, $path, $handler, array $methods, array $option = []): void
    {
        $this->router->addRoute(new RouteData($name, $path, $handler, $methods, $option));
    }

    public function any($name, $path, $handler, array $option = []): void
    {
        $this->route($name, $path, $handler, [], $option);
    }

    public function get($name, $path, $handler, array $option = []): void
    {
        $this->route($name, $path, $handler, ['GET'], $option);
    }

    public function post($name, $path, $handler, array $option = []): void
    {
        $this->route($name, $path, $handler, ['POST'], $option);
    }


}