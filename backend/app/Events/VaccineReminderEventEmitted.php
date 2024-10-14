<?php
 
namespace App\Events;

use Illuminate\Queue\SerializesModels;
 
class VaccineReminderEventEmitted
{
    use SerializesModels;

    public array $mailData;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $mailData)
    {
        $this->mailData = $mailData;
    }

}