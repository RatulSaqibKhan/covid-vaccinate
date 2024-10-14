<?php

namespace App\Services\V1;

use App\DTOs\MailDTO;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendEmail(MailDTO $mailDto)
    {
        Mail::raw($mailDto->message, function ($message) use ($mailDto) {
            $message->to($mailDto->email)
                ->subject($mailDto->subject);
        });
    }
}
