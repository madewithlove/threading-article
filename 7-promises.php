<?php


class Promise extends Thread
{
    /**
     * @var Closure
     */
    protected $closure;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * Promise constructor.
     *
     * @param Closure $closure
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
        $this->start();
    }

    public function run()
    {
        $this->synchronized(function () {
            $closure = $this->closure;

            $this->result = $closure();
            $this->notify();
        });
    }

    public function then(callable $callback)
    {
        return $this->synchronized(function () use ($callback) {
            if (!$this->result) {
                $this->wait();
            }

            $callback($this->result);
        });
    }
}

$promise = new Promise(function () {
    return file_get_contents('http://google.fr');
});

$promise->then(function ($results) {
   echo $results;
});
