<?php


namespace Framework\Http;


class ActionResolver
{
    /**
     * @param $handler
     * @return callable
     */
    public function resolve($handler): callable
    {
        return is_string($handler) ? new $handler() : $handler;
    }
}