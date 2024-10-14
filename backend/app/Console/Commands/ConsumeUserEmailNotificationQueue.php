<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailJob;
use App\Library\PlainAMQPManager;
use Illuminate\Console\Command;

class ConsumeUserEmailNotificationQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:consume-user-email-notification-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume user email notification queue from RabbitMQ';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queueName = \config('queue.connections.rabbitmq.queues.user_email_notification.name');
        
        // Callback function to process the queue message
        $callback = function ($msg) {
            $userData = json_decode($msg->body, true);
            
            // Dispatch a job to process the user registration
            SendEmailJob::dispatch($userData);
        };
        
        app(PlainAMQPManager::class)->basicConsume($queueName, $callback);
    }
}
