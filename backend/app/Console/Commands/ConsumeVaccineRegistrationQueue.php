<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessVaccineScheduling;
use App\Library\PlainAMQPManager;

class ConsumeVaccineRegistrationQueue extends Command
{
    protected $signature = 'queue:consume-vaccine-registration';
    protected $description = 'Consume vaccine registration queue from RabbitMQ';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $queueName = \config('queue.connections.rabbitmq.queues.user_register.name');
        
        // Callback function to process the queue message
        $callback = function ($msg) {
            $userData = json_decode($msg->body, true);
            
            // Dispatch a job to process the user registration
            ProcessVaccineScheduling::dispatch($userData);
        };
        
        app(PlainAMQPManager::class)->basicConsume($queueName, $callback);
    }
}
