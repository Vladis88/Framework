<?php

namespace Framework\View\Twig;

use Twig\Environment;

class TwigRender
{
    private Environment $twig;
    private $extension;

    /**
     * TwigRender constructor.
     * @param \Twig\Environment $twig
     * @param $extension
     */
    public function __construct(Environment $twig, $extension)
    {
        $this->twig = $twig;
        $this->extension = $extension;
    }


    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function render($name, array $params = []): string
    {
        return $this->twig->render($name . $this->extension, $params);
    }

}