<?php

namespace Framework\View;

class PhpViewRender
{
    private $path;
    private $extend;
    private $params = [];
    private $blocks = [];

    /**
     * ViewRender constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
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

        return $this->render($this->extend, [
            'content' => $content,
        ]);
    }

    public function extend($view): void
    {
        $this->extend = $view;
    }

    public function startBlock()
    {
        ob_start();
    }

    public function endBlock(string $string)
    {
        $this->blocks[$string] = ob_get_clean();
    }

    public function renderBlock(string $string)
    {
        return $this->blocks[$string] ?? '';
    }

}