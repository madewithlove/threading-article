<?php

class ClosureRunner extends Threaded
{
    public function __construct($closure)
    {
        $this->closure = $closure;
    }

    public function run()
    {
        $closure = $this->closure;
        $closure();
    }
}

$foo = 'test';

$pool = new Pool(5, Worker::class);
$pool->submit(new ClosureRunner(function () use ($foo) {
    var_dump($foo);
}));

$pool->shutdown();

// Passing example
//////////////////////////////////////////////////////////////////////

$pool = new Pool(5, Worker::class);

$foo = 'test';
$pool->submit(Collectable::from(function () use ($foo) {
    var_dump($foo);
    $this->setGarbage();
}));

$pool->shutdown();
