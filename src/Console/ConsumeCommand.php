<?php

namespace VladimirYuldashev\LaravelQueueRabbitMQ\Console;

use Illuminate\Queue\Console\WorkCommand;
use VladimirYuldashev\LaravelQueueRabbitMQ\Consumer;

class ConsumeCommand extends WorkCommand
{
    protected $signature = 'rabbitmq:consume
                            {connection? : The name of the queue connection to work}
                            {--queue= : The names of the queues to work}
                            {--once : Only process the next job on the queue}
                            {--stop-when-empty : Stop when the queue is empty}
                            {--delay=0 : The number of seconds to delay failed jobs}
                            {--force : Force the worker to run even in maintenance mode}
                            {--memory=128 : The memory limit in megabytes}
                            {--sleep=3 : Number of seconds to sleep when no job is available}
                            {--timeout=60 : The number of seconds a child process can run}
                            {--tries=1 : Number of times to attempt a job before logging it failed}
                           
                            {--consumer-tag}
                            {--no-local}
                            {--no-ack}
                            {--exclusive}
                            {--prefetch-size=0}
                            {--prefetch-count=1000}
                           ';

    protected $description = 'Consume messages';

    public function handle(): void
    {
        /** @var Consumer $consumer */
        $consumer = $this->worker;

        $consumer->setContainer($this->laravel);
        $consumer->setConsumerTag(config('app.name').'_'.getmypid());
        $consumer->setNoLocal($this->option('no-local'));
        $consumer->setNoAck($this->option('no-ack'));
        $consumer->setExclusive($this->option('exclusive'));
        $consumer->setPrefetchSize((int) $this->option('prefetch-size'));
        $consumer->setPrefetchCount((int) $this->option('prefetch-count'));

        parent::handle();
    }
}
