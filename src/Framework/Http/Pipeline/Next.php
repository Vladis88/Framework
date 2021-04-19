<?php


namespace Framework\Http\Pipeline;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Next
{
    private $default;
    private \SplQueue $queue;
    private ResponseInterface $response;

    /**
     * Next constructor.
     * @param \SplQueue $queue
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $default
     */
    public function __construct(\SplQueue $queue, ResponseInterface $response, callable $default)
    {
        $this->queue = $queue;
        $this->default = $default;
        $this->response = $response;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($this->queue->isEmpty()) {
            return ($this->default)($request);
        }

        $current = $this->queue->dequeue();

        return $current($request, $this->response, function (ServerRequestInterface $request) {
            return $this($request);
        });
    }


}