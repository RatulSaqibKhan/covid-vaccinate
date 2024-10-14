<?php

namespace App\Console\Commands;

use App\DTOs\MailDTO;
use App\Events\VaccineReminderEventEmitted;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendVaccineReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-vaccine-reminder-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send vaccine reminder to user';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get tomorrow's date
        $tomorrow = Carbon::tomorrow(config('app.timezone'))->toDateString();

        $counter = 0;
        // Query users whose vaccine scheduled date is tomorrow
        DB::table('users')
            ->where('scheduled_date', $tomorrow)
            ->where('status', 'Scheduled')
            ->orderBy('id')
            ->chunk(100, function ($users) use (&$counter) {
                foreach ($users as $user) {
                    $name = $user->name;
                    $email = $user->email;
                    $subject = 'Vaccination Reminder';
                    $message = "Dear {$user->name}, your vaccination is scheduled at {$user->scheduled_date}. Please be prepared.";
                    
                    // Prepare email data using DTO
                    $mailDTO = new MailDTO(
                        $name,
                        $email,
                        $subject,
                        $message
                    );

                    \event(new VaccineReminderEventEmitted($mailDTO->toArray()));
                    ++$counter;
                }
            });

        // Log the result
        $this->info('Reminder emails will be sent to  ' . $counter . ' users.');
    }
}
