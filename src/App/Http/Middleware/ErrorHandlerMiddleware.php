<?php

namespace App\Http\Middleware;

use Framework\View\Php\PhpViewRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorHandlerMiddleware
{
    private bool $debug;
    private PhpViewRender $template;

    /**
     * ErrorHandlerMiddleware constructor.
     * @param bool $debug
     * @param \Framework\View\Php\PhpViewRender $template
     */
    public function __construct(bool $debug, PhpViewRender $template)
    {
        $this->debug = $debug;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (\Throwable $e) {
            $view = $this->debug ? 'error/error_debug' : 'error/error';
            return new HtmlResponse($this->template->render($view, [
                'request' => $request,
                'exception' => $e,
            ]), $e->getCode() ?: 500);
        }
    }
}