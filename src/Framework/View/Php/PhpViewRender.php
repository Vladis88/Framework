<?php

namespace Framework\View\Php;


class PhpViewRender
{
    private $path;
    private $extend;
    private array $blocks = [];
    private \SplStack $blockNames;
    private array $extensions = [];

    /**
     * ViewRender constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->blockNames = new \SplStack();
    }

    public function addExtension(Extension $extension): void
    {
        $this->extensions[] = $extension;
    }

    public function render($view, array $params = []): string
    {
        $level = ob_get_level();
        try {
            $viewFile = $this->path . '/' . $view . '.php';
            ob_start();
            extract($params, EXTR_OVERWRITE);
            $this->extend = null;
            require $viewFile;
            $content = ob_get_clean();

            if (!$this->extend) {
                return $content;
            }
        } catch (\Throwable | \Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
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

    public function __call($name, $arguments)
    {
        foreach ($this->extensions as $extension) {
            $functions = $extension->getFunctions();
            foreach ($functions as $function) {
                if ($function->name === $name) {
                    if ($function->needRenderer) {
                        return ($function->callback)($this, ...$arguments);
                    }
                    return ($function->callback)(...$arguments);
                }
            }
        }
        throw new \InvalidArgumentException('Undefined function "' . $name . '"');
    }

}