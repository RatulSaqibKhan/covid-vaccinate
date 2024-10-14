<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send-test-email', function () {
    $details = [
        'subject' => 'Test Email',
        'body' => 'This is a test email using Mailhog in Laravel.'
    ];

    \Mail::raw($details['body'], function ($message) use ($details) {
        $message->to('recipient@example.com')
                ->subject($details['subject']);
    });

    return 'Test email sent!';
});