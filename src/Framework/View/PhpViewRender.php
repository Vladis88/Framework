<?php

namespace Framework\View;

class PhpViewRender
{
    private $path;
    private $extend;
    private array $blocks = [];
    private \SplStack $blockNames;

    /**
     * ViewRender constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->blockNames = new \SplStack();
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

    public function startBlock(string $string)
    {
        $this->blockNames->push($string);
        ob_start();
    }

    public function endBlock()
    {
        $string = $this->blockNames->pop();
        $this->blocks[$string] = ob_get_clean();
    }

    public function renderBlock(string $string)
    {
        return $this->blocks[$string] ?? '';
    }

}