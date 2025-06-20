<?php

namespace App\Providers;

use App\Models\DailySummary;
use App\Observers\ReadOnlyObserver;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DailySummary::observe(ReadOnlyObserver::class);

        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('apple', \SocialiteProviders\Apple\Provider::class);
        });

        Scramble::registerApi('v1', [
            'api_path' => 'api/v1',
            'info' => ['version' => '1.0'],
            'servers' => [
                'Test' => 'http://bofur.oma.be:8000/api/v1',
                'Local' => 'api/v1',
            ],
        ])
            ->expose(
                ui: '/docs/api/v1',
                document: '/docs/api/v1/openapi.json'
            );
    }
}
