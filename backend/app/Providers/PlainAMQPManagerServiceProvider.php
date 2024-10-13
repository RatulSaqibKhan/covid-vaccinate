<?php
namespace App\Providers;

use App\Library\PlainAMQPManager;
use Illuminate\Support\ServiceProvider;

class PlainAMQPManagerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(PlainAMQPManager::class, function () {
            return new PlainAmqpManager();
        });
    }
}