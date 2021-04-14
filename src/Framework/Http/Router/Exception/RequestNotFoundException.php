<?php


namespace Framework\Http\Router\Exception;


class RequestNotFoundException extends \LogicException
{
    private $name;
    private array $params;

    /**
     * RequestNotFoundException constructor.
     * @param mixed $name
     * @param array $params
     */
    public function __construct($name, array $params)
    {
        parent::__construct('Route "' . $name . '" not found');
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }


}