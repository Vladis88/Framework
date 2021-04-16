<?php

namespace Framework\Http\Router;

use Aura\Router\Exception\RouteNotFound;
use Aura\Router\RouterContainer;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;

class AuraRouterAdapter implements Router
{
    /**
     * @var RouterContainer
     */
    private RouterContainer $aura;

    /**
     * AuraRouterAdapter constructor.
     * @param RouterContainer $aura
     */
    public function __construct(RouterContainer $aura)
    {
        $this->aura = $aura;
    }


    /**
     * @param ServerRequestInterface $request
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result
    {
        $matcher = $this->aura->getMatcher();
        if ($route = $matcher->match($request)) {
            return new Result($route->name, $route->handler, $route->attributes);
        }
        throw new RequestNotMatchedException($request);
    }

    /**
     * @param $name
     * @param array $params
     * @return string
     */
    public function generate($name, array $params = []): string
    {
        $generate = $this->aura->getGenerator();
        try {
            return $generate->generate($name, $params);
        } catch (RouteNotFound $e) {
            throw new RouteNotFoundException($name, $params);
        }
    }
}