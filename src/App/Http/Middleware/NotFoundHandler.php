<?php

namespace App\Http\Middleware;

use Framework\View\PhpViewRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class NotFoundHandler
{
    private PhpViewRender $template;

    /**
     * NotFoundHandler constructor.
     * @param \Framework\View\PhpViewRender $template
     */
    public function __construct(PhpViewRender $template)
    {
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        return new HtmlResponse($this->template->render('error/404', [
            'request' => $request,
        ]), 404);
    }

}