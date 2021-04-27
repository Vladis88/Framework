<?php

namespace App\Http\Middleware;

use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class NotFoundHandler
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
    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        return new HtmlResponse($this->template->render('error/404', [
            'request' => $request,
        ]), 404);
    }

}