<?php


namespace Framework\View\Php;


class SimpleFunction
{
    public string $name;
    public $callback;
    public bool $needRender;

    /**
     * SimpleFunction constructor.
     * @param string $name
     * @param callable $callback
     * @param bool $needRender
     */
    public function __construct(string $name, callable $callback, bool $needRender = false)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->needRender = $needRender;
    }


}