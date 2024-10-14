<?php

namespace App\Jobs;

use App\DTOs\MailDTO;
use App\Services\V1\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public array $mailData;
    public ?string $name;
    public ?string $email;
    public ?string $subject;
    public ?string $message;

    /**
     * Create a new job instance.
     */
    public function __construct(array $mailData)
    {
        if (array_key_exists('data', $mailData)) {
            $this->mailData = $mailData['data'];
            if (array_key_exists('name', $mailData['data'])) 
            $this->name = array_key_exists('name', $this->mailData) ? $this->mailData['name'] : null;
            $this->email = array_key_exists('email', $this->mailData) ? $this->mailData['email'] : null;
            $this->subject = array_key_exists('subject', $this->mailData) ? $this->mailData['subject'] : null;
            $this->message = array_key_exists('message', $this->mailData) ? $this->mailData['message'] : null;
        }

    }

    /**
     * Execute the job.
     */
    public function handle(EmailService $service)
    {
        if ($this->name && $this->email && $this->subject && $this->message) {
            $mailDTO = new MailDTO(
                $this->name,
                $this->email,
                $this->subject,
                $this->message
            );
            $service->sendEmail($mailDTO);
        }
    }
}
