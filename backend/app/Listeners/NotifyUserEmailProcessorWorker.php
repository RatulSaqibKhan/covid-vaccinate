<?php

namespace App\Listeners;

use App\DTOs\CloudEventDTO;
use App\DTOs\EventEmitterDTO;
use App\Events\VaccineReminderEventEmitted;
use App\Library\PlainAMQPManager;

class NotifyUserEmailProcessorWorker
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\VaccineReminderEventEmitted  $event
     * @return void
     */
    public function handle(VaccineReminderEventEmitted $event)
    {
        $cloudEvents = CloudEventDTO::handle(new EventEmitterDTO(
            type: "covid_vaccinate_user_notifier",
            subject: "User Vaccination Notify",
            data: $event->mailData
        ))->toArray();

        app(PlainAMQPManager::class)->pushToExchange(
            json_encode($cloudEvents),
            config('queue.connections.rabbitmq.queues.user_email_notification.name')
        );
    }
}
