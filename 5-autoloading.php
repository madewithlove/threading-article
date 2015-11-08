<?php
require 'vendor/autoload.php';

class Example extends Thread
{
    public function run()
    {
        dump('foobar');
    }
}

// Faulty example
//////////////////////////////////////////////////////////////////////

$job = new Example();
$job->start();
$job->join();

// Now let's fix our example
//////////////////////////////////////////////////////////////////////

class AutoloadingWorker extends Worker
{
    public function run()
    {
        require 'vendor/autoload.php';
    }
}

// Create our worker and stack our job on it
$worker = new AutoloadingWorker();

$job = new Example();
$worker->stack($job);

$worker->start();
$worker->join();

// Or use a pool and specify our custom worker
$pool = new Pool(5, AutoloadingWorker::class);
$pool->submit(new Example());
$pool->shutdown();
