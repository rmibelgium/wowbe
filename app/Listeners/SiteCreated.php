<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;

class SiteCreated
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(\App\Events\SiteCreated $event): void
    {
        $site = $event->site;
        $locale = app()->getLocale();

        Mail::to($site->user)
            ->locale($locale)
            ->send(new \App\Mail\SiteCreated($site));
    }
}
