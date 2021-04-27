<?php

namespace Framework\View;

use Framework\Http\Router\Router;

class PhpViewRender
{
    private $path;
    private $extend;
    private array $blocks = [];
    private \SplStack $blockNames;
    private Router $router;

    /**
     * ViewRender constructor.
     * @param $path
     * @param \Framework\Http\Router\Router $router
     */
    public function __construct($path, Router  $router)
    {
        $this->path = $path;
        $this->blockNames = new \SplStack();
        $this->router = $router;
    }

    public function render($view, array $params = []): string
    {
        $viewFile = $this->path . '/' . $view . '.php';
        ob_start();
        extract($params, EXTR_OVERWRITE);
        $this->extend = null;
        require $viewFile;
        $content = ob_get_clean();

        if (!$this->extend) {
            return $content;
        }

        return $this->render($this->extend);
    }

    public function extend($view): void
    {
        $this->extend = $view;
    }

    public function startBlock(string $string)
    {
        $this->blockNames->push($string);
        ob_start();
    }

    public function endBlock()
    {
        $content = ob_get_clean();
        $string = $this->blockNames->pop();
        if ($this->hasBlock($string)) {
            return;
        }
        $this->blocks[$string] = $content;
    }

    public function renderBlock(string $string)
    {
        $block = $this->blocks[$string] ?? '';
        if ($block instanceof \Closure) {
            return $block();
        }
        return $block ?? '';
    }

    public function hasBlock(string $string): bool
    {
        return array_key_exists($string, $this->blocks);
    }

    public function ensureBlock(string $string): bool
    {
        if ($this->hasBlock($string)) {
            return false;
        }
        $this->startBlock($string);
        return true;
    }

    public function block(string $string, $content)
    {
        if ($this->hasBlock($string)) {
            return;
        }
        $this->blocks[$string] = $content;
    }

    public function encode(string $name): string
    {
        return htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    public function path(string $string, array $params = []): string
    {
        return $this->router->generate($string, $params);
    }

}