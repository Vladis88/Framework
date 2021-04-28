<?php

namespace App\Http\Middleware\ErrorHandler;

use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HtmlErrorResponseGenerator implements ErrorResponseGenerator
{
    private bool $debug;
    private TwigRender $template;

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
    public function generate(ServerRequestInterface $request, \Throwable $e): ResponseInterface
    {
        $view = $this->debug ? 'error/error-debug' : 'error/error';
        return new HtmlResponse($this->template->render($view, [
            'request' => $request,
            'exception' => $e,
        ]), self::getStatusCode($e));
    }

    private static function getStatusCode(\Throwable $e): int
    {
        $code = $e->getCode();
        if ($code >= 400 && $code < 600) {
            return $code;
        }
        return 500;
    }
}