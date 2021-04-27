<?php


namespace Framework\View\Php;


abstract class Extension
{
    /**
     * @return SimpleFunction[]
     */
    public function getFunctions():array
    {
        return [];
    }

}