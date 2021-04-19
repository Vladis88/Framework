<?php


namespace Framework\Http\Pipeline;


class UnknownMiddlewareTypeException extends \InvalidArgumentException
{
    private $type;

    /**
     * UnknownMiddlewareTypeException constructor.
     * @param $type
     */
    public function __construct($type)
    {
        parent::__construct('Unknown middleware type');
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }




}