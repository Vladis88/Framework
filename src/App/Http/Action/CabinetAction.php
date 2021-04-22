<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthMiddleware;
use Framework\View\PhpViewRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    private PhpViewRender $template;

    public function __construct(PhpViewRender $template)
    {
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {

        $username = $request->getAttribute(BasicAuthMiddleware::ATTRIBUTE);

        return new HtmlResponse($this->template->render('app/cabinet', [
            'name' => $username
        ]));
    }

}