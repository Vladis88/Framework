<?php

namespace Framework\View;

class PhpViewRender implements ViewRender
{
    private $path;

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
        require $viewFile;
        return ob_get_clean();
    }

}