<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test email.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Mail::raw('This is a test email.', function ($message) {
            $message
                ->to($this->argument('email'))
                ->subject('Test Email');
        });

        $this->info('Test email sent successfully.');
    }
}
