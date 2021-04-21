<?php

namespace App\Http\Action;

use Framework\View\ViewRender;
use Zend\Diactoros\Response\HtmlResponse;

class AboutAction
{
    private ViewRender $template;

    public function __construct(ViewRender $template)
    {
        $this->template = $template;
    }

    public function __invoke(): HtmlResponse
    {
        return new HtmlResponse($this->template->render('about'));
    }

}