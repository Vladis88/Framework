<?php

namespace App\Http\Action;

use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HomeAction
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
    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        return new HtmlResponse($this->view->render('app/home', [
            'name' => $name
        ]));
    }

}