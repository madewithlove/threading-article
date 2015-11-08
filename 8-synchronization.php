<?php
class Job extends Thread
{
    public function run()
    {
        while (!$this->isWaiting()) {
            // Do some work here as long
            // as we're not waiting for parent
        }

        $this->synchronized(function () {
            // Tell parent my work here is done
            $this->result = 'Finished at '.time();
            $this->notify();
        });
    }
}

$job = new Job();
$job->start();

// Do some operation in the main thread here
echo 'Started at '.time().PHP_EOL;
sleep(1);

// Notify the child thread that we need it
// and get its results
$job->synchronized(function ($job) {
    $job->wait();
    echo $job->result;
}, $job);
