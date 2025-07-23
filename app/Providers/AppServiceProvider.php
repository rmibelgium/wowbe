<?php

namespace App\Providers;

use App\Models\DayAggregate;
use App\Models\FiveMinutesAggregate;
use App\Observers\ReadOnlyObserver;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\ServiceProvider;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // @codeCoverageIgnoreStart
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FiveMinutesAggregate::observe(ReadOnlyObserver::class);
        DayAggregate::observe(ReadOnlyObserver::class);

        Health::checks([
            Checks\CacheCheck::new(),
            CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLastMinute(10.0)
                ->failWhenLoadIsHigherInTheLast5Minutes(5.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(3.0),
            Checks\DatabaseCheck::new(),
            Checks\DatabaseConnectionCountCheck::new()
                ->warnWhenMoreConnectionsThan(50)
                ->failWhenMoreConnectionsThan(100),
            Checks\DatabaseSizeCheck::new()
                ->failWhenSizeAboveGb(5.0),
            Checks\DatabaseTableSizeCheck::new()
                ->table('observations', 1024),
            Checks\DebugModeCheck::new(),
            Checks\EnvironmentCheck::new(),
            Checks\OptimizedAppCheck::new(),
            Checks\ScheduleCheck::new(),
            SecurityAdvisoriesCheck::new(),
            Checks\UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(60)
                ->failWhenUsedSpaceIsAbovePercentage(80),
        ]);

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
