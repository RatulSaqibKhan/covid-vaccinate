<?php

namespace App\Providers;

use App\Events\UserRegisterEventEmitted;
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
