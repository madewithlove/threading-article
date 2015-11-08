<?php

class SearchGoogle extends Collectable
{
    /**
     * @var string
     */
    public $query;

    /**
     * @var string
     */
    public $html;

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

        $this->html = file_get_contents('http://google.fr?q='.$this->query);
        $this->setGarbage();
    }
}

class SearchPool extends Pool
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @return array
     */
    public function process()
    {
        // Run this loop as long as we have
        // jobs in the pool
        while (count($this->work)) {
            $this->collect(function (SearchGoogle $job) {
                // If a job was marked as done
                // collect its results
                if ($job->isGarbage()) {
                    $this->data[$job->query] = $job->html;
                }

                return $job->isGarbage();
            });
        }

        // All jobs are done
        // we can shutdown the pool
        $this->shutdown();

        return $this->data;
    }
}

// Create a pool and submit jobs to it
$pool = new SearchPool(5, Worker::class);
$pool->submit(new SearchGoogle('cats'));
$pool->submit(new SearchGoogle('dogs'));
$pool->submit(new SearchGoogle('birds'));
$pool->submit(new SearchGoogle('planes'));
$pool->submit(new SearchGoogle('cars'));

$data = $pool->process();
var_dump($data);
