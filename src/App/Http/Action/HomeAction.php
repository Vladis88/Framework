<?php

namespace App\Http\Action;

use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HomeAction implements RequestHandlerInterface
{
    private TwigRender $view;

    /**
     * HomeAction constructor.
     * @param TwigRender $view
     */
    public function __construct(TwigRender $view)
    {
        $this->view = $view;
    }

    /**
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\LoaderError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        return new HtmlResponse($this->view->render('app/home', [
            'name' => $name
        ]));
    }

}