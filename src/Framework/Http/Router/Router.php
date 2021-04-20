<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

interface Router
{
    /**
     * @param ServerRequestInterface $request
     * @return Result
     * @throws RouteNotFoundException
     */
    public function match(ServerRequestInterface $request): Result;

    /**
     * @param $name
     * @param array $params
     * @return string
     * @throws RouteNotFoundException
     */
    public function generate($name, array $params = []): string;

    public function addRoute(RouteData $data): void;
}