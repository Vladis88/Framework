<?php

namespace Framework\View;

interface ViewRender
{
    public function render($view, array $params = []);
}