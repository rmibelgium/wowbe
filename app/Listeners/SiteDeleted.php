<?php

namespace App\Listeners;

class SiteDeleted
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(\App\Events\SiteDeleted $event): void
    {
        $site = $event->site;

        // Delete all observations associated with the site
        $site->observations()->delete();
    }
}
