<?php

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\RouteData;
use Framework\Http\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stratigility\Middleware\PathMiddlewareDecorator;
use Zend\Stratigility\MiddlewarePipe;

class Application implements MiddlewareInterface, RequestHandlerInterface
{
    private MiddlewareResolver $resolver;
    private RequestHandlerInterface $default;
    private Router $router;
    private MiddlewarePipe $pipeline;

    /**
     * Application constructor.
     * @param \Framework\Http\Pipeline\MiddlewareResolver $resolver
     * @param \Framework\Http\Router\Router $router
     * @param \Psr\Http\Server\RequestHandlerInterface $default
     */
    public function __construct(
        MiddlewareResolver $resolver,
        Router $router,
        RequestHandlerInterface $default)
    {
        $this->resolver = $resolver;
        $this->router = $router;
        $this->pipeline = new MiddlewarePipe();
        $this->default = $default;
    }

    public function pipe($path, $middleware = null): void
    {
        if ($middleware === null) {
            $this->pipeline->pipe($this->resolver->resolve($path));
        } else {
            $this->pipeline->pipe(new PathMiddlewareDecorator($path, $this->resolver->resolve($middleware)));
        }
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

    public function put($name, $path, $handler, array $option = []): void
    {
        $this->route($name, $path, $handler, ['PUT'], $option);
    }

    public function patch($name, $path, $handler, array $option = []): void
    {
        $this->route($name, $path, $handler, ['PATCH'], $option);
    }

    public function delete($name, $path, $handler, array $option = []): void
    {
        $this->route($name, $path, $handler, ['DELETE'], $option);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->pipeline->process($request, $this->default);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->pipeline->process($request, $handler);
    }
}