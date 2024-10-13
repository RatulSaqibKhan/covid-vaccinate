<?php

namespace App\Listeners;

use App\DTOs\CloudEventDTO;
use App\DTOs\EventEmitterDTO;
use App\Events\UserRegisterEventEmitted;
use App\Library\PlainAMQPManager;

class NotifyUserVaccineSchedulerWorker
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
     * @param  \App\Events\UserRegisterEventEmitted  $event
     * @return void
     */
    public function handle(UserRegisterEventEmitted $event)
    {
        $cloudEvents = CloudEventDTO::handle(new EventEmitterDTO(
            type: "covid_vaccinate_user_register",
            subject: "User Vaccination Schedling",
            data: $event->user->toArray()
        ))->toArray();

        app(PlainAMQPManager::class)->pushToExchange(
            json_encode($cloudEvents),
            config('queue.connections.rabbitmq.queues.user_register.name')
        );
    }
}
