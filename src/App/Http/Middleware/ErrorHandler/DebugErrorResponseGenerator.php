<?php

namespace App\Http\Middleware\ErrorHandler;

use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Stratigility\Utils;

class DebugErrorResponseGenerator implements ErrorResponseGenerator
{
    private TwigRender $template;
    private string $view;
    private ResponseInterface $response;

    public function __construct(TwigRender $template, ResponseInterface $response, string $view)
    {
        $this->template = $template;
        $this->view = $view;
        $this->response = $response;
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $responseResult = $this->response->withStatus(Utils::getStatusCode($e, $this->response));

        $responseResult->getBody()->write($this->template->render($this->view, [
            'request' => $request,
            'exception' => $e,
        ]));

        return $responseResult;
    }
}