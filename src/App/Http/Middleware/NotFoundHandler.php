<?php


namespace App\Http\Middleware;


use Zend\Diactoros\Response\HtmlResponse;

class NotFoundHandler
{
    public function __invoke(): HtmlResponse
    {
        return new HtmlResponse('Undefined page', 404);
    }

}