<?php

namespace App\Http\Middleware;

use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorHandlerMiddleware
{
    private bool $debug;
    private TwigRender $template;

    /**
     * ErrorHandlerMiddleware constructor.
     * @param bool $debug
     * @param \Framework\View\Twig\TwigRender $template
     */
    public function __construct(bool $debug, TwigRender $template)
    {
        $this->debug = $debug;
        $this->template = $template;
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
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