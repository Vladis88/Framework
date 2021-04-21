<?php

namespace App\Http\Action;

use Framework\View\ViewRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HomeAction
{
    private ViewRender $view;

    /**
     * HomeAction constructor.
     * @param ViewRender $view
     */
    public function __construct(ViewRender $view)
    {
        $this->view = $view;
    }

    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        return new HtmlResponse($this->view->render('home', [
            'name' => $name
        ]));
    }

}