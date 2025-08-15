<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;

class AccountCreated
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(\App\Events\AccountCreated $event): void
    {
        $user = $event->user;

        Mail::to($user)
            ->send(new \App\Mail\AccountCreated($user));
    }
}
