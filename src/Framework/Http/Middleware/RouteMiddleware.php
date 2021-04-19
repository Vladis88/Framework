<?php


namespace Framework\Http\Middleware;


use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

class RouteMiddleware
{
    private Router $router;
    private MiddlewareResolver $resolver;

    /**
     * RouteMiddleware constructor.
     * @param \Framework\Http\Router\Router $router
     * @param \Framework\Http\Pipeline\MiddlewareResolver $resolver
     */
    public function __construct(Router $router, MiddlewareResolver $resolver)
    {
        $this->router = $router;
        $this->resolver = $resolver;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            $result = $this->router->match($request);
            foreach ($result->getAttributes() as $attribute => $value) {
                $request = $request->withAttribute($attribute, $value);
            }
            $middleware = $this->resolver->resolve($result->getHandler());
            return $middleware($request, $next);
        }catch (RequestNotMatchedException $e){
            return $next($request);
        }
    }


}