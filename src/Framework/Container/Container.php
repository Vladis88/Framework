<?php

namespace Framework\Container;


class Container
{
    private array $definitions = [];

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ServiceNotFoundException("Undefined parameter\"" . $id . '"');
        }
        return $this->definitions[$id];
    }

    /**
     * @param $id
     * @param $value
     */
    public function set($id, $value): void
    {
        $this->definitions[$id] = $value;
    }

}