<?php
define('MY_CONSTANT', true);
function test()
{
}

class Foobar
{
}

class Example extends Thread
{
    public function run()
    {
        var_dump(defined('MY_CONSTANT'));
        var_dump(function_exists('test'));
        var_dump(class_exists('Foobar'));
    }
}

// true true true
$job = new Example();
$job->start(); // default argument is PTHREADS_INHERIT_ALL
$job->join();

// false false true
$job = new Example();
$job->start(PTHREADS_INHERIT_CLASSES);
$job->join();

// true true true
$job = new Example();
$job->start(PTHREADS_INHERIT_CLASSES | PTHREADS_INHERIT_CONSTANTS | PTHREADS_INHERIT_FUNCTIONS);
$job->join();
