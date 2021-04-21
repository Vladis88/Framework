<?php

namespace App\Http\Action;

use Framework\View\PhpViewRender;
use Zend\Diactoros\Response\HtmlResponse;

class AboutAction
{
    private PhpViewRender $template;

    public function __construct(PhpViewRender $template)
    {
        $this->template = $template;
    }

    public function __invoke(): HtmlResponse
    {
        return new HtmlResponse($this->template->render('about'));
    }

}