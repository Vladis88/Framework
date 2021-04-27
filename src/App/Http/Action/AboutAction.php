<?php

namespace App\Http\Action;

use Framework\View\Twig\TwigRender;
use Zend\Diactoros\Response\HtmlResponse;

class AboutAction
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
    public function __invoke(): HtmlResponse
    {
        return new HtmlResponse($this->template->render('app/about'));
    }

}