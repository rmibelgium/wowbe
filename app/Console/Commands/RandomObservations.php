<?php

namespace App\Console\Commands;

use App\Models\Site;
use Database\Factories\ObservationFactory;
use Illuminate\Console\Command;

class RandomObservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:random-observations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate random observations for every site with current datetime.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Site::each(function (Site $site) {
            $this->info("Processing site: {$site->name}");

            ObservationFactory::new(['dateutc' => now()])
                ->for($site)
                ->create();
        });
    }
}
