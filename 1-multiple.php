<?php
include 'SearchGoogle.php';

$searches = ['cats', 'dogs', 'birds'];
foreach ($searches as &$search) {
    $search = new SearchGoogle($search);
    $search->start();
}

foreach ($searches as $search) {
    $search->join();
    echo substr($search->html, 0, 20).PHP_EOL;
}
