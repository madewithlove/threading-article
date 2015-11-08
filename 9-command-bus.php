<?php
use League\Tactician\CommandBus;

require 'vendor/autoload.php';

class CommandBusWorker extends Worker
{
    /**
     * @var CommandBus
     */
    public $bus;

    /**
     * CommandBusWorker constructor.
     *
     * @param CommandBus $bus
     */
    public function __construct(CommandBus $bus)
    {
        $this->bus = $bus;
    }

    public function run()
    {
        require 'vendor/autoload.php';
    }

    /**
     * @param object $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        return $this->bus->handle($command);
    }
}

class CommandJob extends Collectable
{
    /**
     * @var object
     */
    public $command;

    /**
     * @param object $command
     */
    public function __construct($command)
    {
        $this->command = $command;
    }

    public function run()
    {
        $this->worker->handle($this->command);
    }
}

$pool = new Pool(5, CommandBusWorker::class, [new CommandBus()]);

$pool->submit(new CommandJob(new Command($some, $arguments)));
$pool->submit(new CommandJob(new AnotherCommand($some, $arguments)));

// With special pool
//////////////////////////////////////////////////////////////////////

class CommandBusPool extends Pool
{
    public function __construct(CommandBus $bus)
    {
        parent::__construct(5, CommandBusWorker::class, [$bus]);
    }

    public function submit($command)
    {
        return parent::submit(new CommandJob($command));
    }
}

$pool = new CommandBusPool(new CommandBus());
$pool->submit(new Command($some, $argument));
