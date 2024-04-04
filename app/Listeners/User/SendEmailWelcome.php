<?php

namespace App\Listeners\User;

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class SendEmailWelcome
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        Mail::to($event->user)->send(new WelcomeMail($event->user));
    }
}
