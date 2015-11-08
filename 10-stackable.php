<?php

class Results extends Stackable
{
}

class Job extends Thread
{
    public function __construct($test)
    {
        $this->test = $test;
        $this->start();
    }

    public function run()
    {
        $this->test[] = rand(0, 10);
    }
}

$results = new Results();

$jobs = [];
while (@$i++ < 100) {
    $jobs[] = new Job($results);
}

foreach ($jobs as $job) {
    $job->join();
}

// Should print 100
var_dump(count($results));
