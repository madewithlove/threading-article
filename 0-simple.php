<?php
include 'SearchGoogle.php';

$job = new SearchGoogle('cats');
$job->start();

// Wait for the job to be finished and print results
$job->join();

echo substr($job->html, 0, 20);


