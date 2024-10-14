<?php

namespace App\Providers;

use App\Events\UserRegisterEventEmitted;
use App\Events\VaccineReminderEventEmitted;
use App\Listeners\NotifyUserEmailProcessorWorker;
use App\Listeners\NotifyUserVaccineSchedulerWorker;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserRegisterEventEmitted::class => [
            NotifyUserVaccineSchedulerWorker::class,
        ],
        VaccineReminderEventEmitted::class => [
            NotifyUserEmailProcessorWorker::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
