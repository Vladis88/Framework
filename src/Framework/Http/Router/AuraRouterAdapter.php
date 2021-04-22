<?php

namespace Framework\Http\Router;

use Aura\Router\Exception\RouteNotFound;
use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use http\Exception\InvalidArgumentException;
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

    function addRoute(RouteData $data): void
    {
        $map = $this->aura->getMap();

        $route = new Route();


        $route->name($data->name);

        $route->path($data->path);
        $route->handler($data->handler);

        foreach ($data->options as $key => $value) {
            switch ($key) {
                case 'tokens':
                    $route->tokens($value);
                    break;
                case 'defaults':
                    $route->defaults($value);
                    break;
                case 'wildcard':
                    $route->wildcard($value);
                    break;
                default:
                    throw new InvalidArgumentException('Undefined option "' . $key . '"');
            }
        }
        if ($data->methods) {
            $route->allows($data->methods);
        }

        $map->addRoute($route);
    }
}