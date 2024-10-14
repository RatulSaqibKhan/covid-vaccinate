<?php

use App\Console\Commands\ConsumeUserEmailNotificationQueue;
use App\Console\Commands\ConsumeVaccineRegistrationQueue;
use App\Console\Commands\SendVaccineReminderCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SendVaccineReminderCommand::class)->dailyAt('21:00')->timezone(config('app.timezone'));
Schedule::command(ConsumeVaccineRegistrationQueue::class)->cron("*/3 * * * *")->timezone(config('app.timezone'));
Schedule::command(ConsumeUserEmailNotificationQueue::class)->cron("*/4 * * * *")->timezone(config('app.timezone'));
