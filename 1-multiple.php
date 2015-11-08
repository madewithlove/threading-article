<?php
include 'SearchGoogle.php';

$searches = ['cats', 'dogs', 'birds'];
foreach ($searches as $key => $search) {
    $searches[$key] = new SearchGoogle($search);
    $searches[$key]->start();
}

foreach ($searches as $search) {
    $search->join();
    echo substr($search->html, 0, 20).PHP_EOL;
}
