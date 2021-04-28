<?php

namespace App\Http\Middleware;

use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class NotFoundHandler implements RequestHandlerInterface
{
    private TwigRender $template;

    /**
     * NotFoundHandler constructor.
     * @param \Framework\View\Twig\TwigRender $template
     */
    public function __construct(TwigRender $template)
    {
        $this->template = $template;
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->template->render('error/404', [
            'request' => $request,
        ]), 404);
    }

}