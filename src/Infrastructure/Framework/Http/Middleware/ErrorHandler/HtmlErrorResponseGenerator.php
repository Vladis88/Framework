<?php

namespace Infrastructure\Framework\Http\Middleware\ErrorHandler;

use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator;
use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\Utils;

class HtmlErrorResponseGenerator implements ErrorResponseGenerator
{
    private TwigRender $template;
    private array $views;
    private ResponseInterface $response;

    public function __construct(TwigRender $template, ResponseInterface $response, array $views)
    {
        $this->template = $template;
        $this->views = $views;
        $this->response = $response;
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $code = Utils::getStatusCode($e, $this->response);
        $responseResult = $this->response->withStatus($code);

        $responseResult->getBody()->write($this->template->render($this->getView($code), [
                'request' => $request,
                'exception' => $e,
            ]));

        return $responseResult;
    }

    private function getView(int $code)
    {
        if (array_key_exists($code, $this->views)) {
            $view = $this->views[$code];
        } else {
            $view = $this->views['error'];
        }
        return $view;
    }
}