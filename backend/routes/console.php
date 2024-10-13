<?php

use App\Console\Commands\ConsumeVaccineRegistrationQueue;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ConsumeVaccineRegistrationQueue::class)->everyMinute();
