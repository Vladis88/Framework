<?php

namespace App\Http\Action;

use Framework\View\Php\PhpViewRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HomeAction
{
    private PhpViewRender $view;

    /**
     * HomeAction constructor.
     * @param PhpViewRender $view
     */
    public function __construct(PhpViewRender $view)
    {
        $this->view = $view;
    }

    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        return new HtmlResponse($this->view->render('app/home', [
            'name' => $name
        ]));
    }

}