<?php


namespace Framework\View\Php\Extension;


use Framework\Http\Router\Router;
use Framework\View\Php\Extension;
use Framework\View\Php\PhpViewRender;
use Framework\View\Php\SimpleFunction;

class RouteExtension extends Extension
{
    private Router $router;

    /**
     * RouteExtension constructor.
     * @param \Framework\Http\Router\Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return SimpleFunction[][]
     */
    public function getFunctions(): array
    {
        return [
            new SimpleFunction('path', [$this, 'generatePath']),
        ];
    }

    public function generatePath($name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }

}