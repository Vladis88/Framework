<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HomeAction
{
    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        return new HtmlResponse('Hello, ' . $name . '!');
    }
}