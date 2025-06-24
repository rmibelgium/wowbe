<?php

namespace App\Console\Commands;

use App\Models\Site;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

use function Laravel\Prompts\multiselect;

class TestEmail extends Command implements PromptsForMissingInput
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
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string,string>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'email' => 'Please enter the recipient email address for the test email(s):',
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // List of mailable classes (in app/Mail directory)
        $mailableFiles = glob(app_path('Mail/*'));
        if ($mailableFiles === false || count($mailableFiles) === 0) {
            $this->fail('Failed to retrieve Mailable files.');
        }
        $mailableClasses = collect($mailableFiles)
            ->map(fn (string $file) => basename($file, '.php'))
            ->filter(fn (string $class) => class_exists(app()->getNamespace().'Mail\\'.$class))
            ->values()
            ->toArray();

        $classes = multiselect('Which email do you want to send?', $mailableClasses);
        $classes = Arr::map($classes, fn (string $class) => app()->getNamespace().'Mail\\'.$class);

        try {
            foreach ($classes as $class) {
                /** @var Mailable $mailable */
                $mailable = match ($class) {
                    \App\Mail\SiteCreated::class => new $class(site: Site::inRandomOrder()->first()),
                    \App\Mail\AccountCreated::class,
                    \App\Mail\AccountDeleted::class => new $class(user: User::inRandomOrder()->first()),
                    default => new $class,
                };

                Mail::to($this->argument('email'))
                    ->send($mailable);
            }

            $this->info('Test email sent successfully.');
        } catch (\Exception $e) {
            $this->fail(sprintf('Failed to send test email: %s (%s:%d)', $e->getMessage(), $e->getFile(), $e->getLine()));
        }
    }
}
