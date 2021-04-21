<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthMiddleware;
use Framework\View\ViewRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    private ViewRender $template;

    public function __construct(ViewRender $template)
    {
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {

        $username = $request->getAttribute(BasicAuthMiddleware::ATTRIBUTE);

        return new HtmlResponse($this->template->render('cabinet', [
            'name' => $username
        ]));
    }

}