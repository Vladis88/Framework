<?php


namespace Framework\Http\Pipeline;


use Psr\Http\Message\ServerRequestInterface;

class Next
{
    private $default;
    private \SplQueue $queue;

    /**
     * Next constructor.
     * @param \SplQueue $queue
     * @param callable $default
     */
    public function __construct(\SplQueue $queue, callable $default)
    {
        $this->queue = $queue;
        $this->default = $default;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($this->queue->isEmpty()) {
            return ($this->default)($request);
        }

        $current = $this->queue->dequeue();

        return $current($request, function (ServerRequestInterface $request) {
            return $this($request);
        });
    }


}