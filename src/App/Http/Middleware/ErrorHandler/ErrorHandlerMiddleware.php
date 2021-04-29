<?php

namespace App\Http\Middleware\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private ErrorResponseGenerator $generator;

    /**
     * ErrorHandlerMiddleware constructor.
     * @param \App\Http\Middleware\ErrorHandler\ErrorResponseGenerator $generator
     */
    public function __construct(ErrorResponseGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            return $this->generator->generate($e, $request);
        }
    }

}