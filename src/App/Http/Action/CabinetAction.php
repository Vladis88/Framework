<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthMiddleware;
use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class CabinetAction implements RequestHandlerInterface
{
    private TwigRender $template;

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

        $username = $request->getAttribute(BasicAuthMiddleware::ATTRIBUTE);

        return new HtmlResponse($this->template->render('app/cabinet', [
            'name' => $username
        ]));
    }

}