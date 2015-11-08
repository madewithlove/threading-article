<?php

class SearchGoogle extends Threaded
{
    /**
     * @var string
     */
    public $query;

    /**
     * @param string $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function run()
    {
        echo microtime(true).PHP_EOL;

        $this->worker->addData(
            file_get_contents('http://google.fr?q='.$this->query)
        );
    }
}

class Searcher extends Worker
{
    /**
     * @var array
     */
    public $data = [];

    public function run()
    {
        echo 'Running '.$this->getStacked().' jobs'.PHP_EOL;
    }

    /**
     * To avoid corrupting the array here
     * we use array_merge here instead of just
     * $this->data[] = $html
     *
     * @param string $data
     */
    public function addData($data)
    {
        $this->data = array_merge($this->data, [$data]);
    }
}

// Stack our jobs on our worker
$worker   = new Searcher();
$searches = ['dogs', 'cats', 'birds'];
foreach ($searches as $key => $search) {
    $searches[$key] = new SearchGoogle($search);
    $worker->stack($searches[$key]);
}

// Start all jobs
$worker->start();

// Join all jobs and close worker
$worker->shutdown();
foreach ($worker->data as $html) {
    echo substr($html, 0, 20).PHP_EOL;
}
