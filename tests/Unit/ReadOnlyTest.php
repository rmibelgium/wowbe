<?php

namespace Tests\Unit;

use App\Exceptions\ReadOnlyException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadOnlyTest extends TestCase
{
    use RefreshDatabase;

    public function test_day_aggregate_cant_be_created(): void
    {
        $this->expectException(ReadOnlyException::class);
        $this->expectExceptionMessage('The model [App\\Models\\DayAggregate] is read-only and cannot be created.');

        $dayAggregate = new \App\Models\DayAggregate;
        $dayAggregate->save();
    }

    public function test_day_aggregate_cant_be_updated(): void
    {
        $this->expectException(ReadOnlyException::class);
        $this->expectExceptionMessage('The model [App\\Models\\DayAggregate] is read-only and cannot be updated.');

        $user = \App\Models\User::factory()->createOne();
        $site = \App\Models\Site::factory()->createOne(['user_id' => $user->id]);
        \App\Models\Observation::factory()->count(5)->create(['site_id' => $site->id]);

        $this->artisan('db:refresh-agg-day')->execute();

        $agg = $site->dayAggregate()->first();
        $agg->name = 'Updated Name';
        $agg->save();
    }

    public function test_day_aggregate_cant_be_deleted(): void
    {
        $this->expectException(ReadOnlyException::class);
        $this->expectExceptionMessage('The model [App\\Models\\DayAggregate] is read-only and cannot be deleted.');

        $user = \App\Models\User::factory()->createOne();
        $site = \App\Models\Site::factory()->createOne(['user_id' => $user->id]);
        \App\Models\Observation::factory()->count(5)->create(['site_id' => $site->id]);

        $this->artisan('db:refresh-agg-day')->execute();

        $agg = $site->dayAggregate()->first();
        $agg->delete();
    }

    public function test_five_minutes_aggregate_cant_be_created(): void
    {
        $this->expectException(ReadOnlyException::class);
        $this->expectExceptionMessage('The model [App\\Models\\FiveMinutesAggregate] is read-only and cannot be created.');

        $dayAggregate = new \App\Models\FiveMinutesAggregate;
        $dayAggregate->save();
    }

    public function test_five_minutes_aggregate_cant_be_updated(): void
    {
        $this->expectException(ReadOnlyException::class);
        $this->expectExceptionMessage('The model [App\\Models\\FiveMinutesAggregate] is read-only and cannot be updated.');

        $user = \App\Models\User::factory()->createOne();
        $site = \App\Models\Site::factory()->createOne(['user_id' => $user->id]);
        \App\Models\Observation::factory()->count(5)->create(['site_id' => $site->id]);

        $this->artisan('db:refresh-agg-5min')->execute();

        $agg = $site->fiveMinutesAggregate()->first();
        $agg->name = 'Updated Name';
        $agg->save();
    }

    public function test_five_minutes_aggregate_cant_be_deleted(): void
    {
        $this->expectException(ReadOnlyException::class);
        $this->expectExceptionMessage('The model [App\\Models\\FiveMinutesAggregate] is read-only and cannot be deleted.');

        $user = \App\Models\User::factory()->createOne();
        $site = \App\Models\Site::factory()->createOne(['user_id' => $user->id]);
        \App\Models\Observation::factory()->count(5)->create(['site_id' => $site->id]);

        $this->artisan('db:refresh-agg-5min')->execute();

        $agg = $site->fiveMinutesAggregate()->first();
        $agg->delete();
    }
}
