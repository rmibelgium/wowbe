<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;

class AccountDeleted
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(\App\Events\AccountDeleted $event): void
    {
        $user = $event->user;
        $locale = app()->getLocale();

        Mail::to($user)
            ->locale($locale)
            ->send(new \App\Mail\AccountDeleted($user));
    }
}
