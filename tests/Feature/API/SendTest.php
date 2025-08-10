<?php

namespace Tests\Feature\API;

use App\Helpers\ObservationHelper;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SendTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_send_observation_via_get(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $hash = $this->faker->sha256();
        $datetime = now()->utc();

        $baromin = $this->faker->randomFloat(2, 28, 31);
        $tempf = $this->faker->randomFloat(2, -40, 212);

        $query = http_build_query([
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $hash,
            'baromin' => $baromin,
            'absbaromin' => ObservationHelper::mslp($baromin, $tempf, $site->altitude),
            'dailyrainin' => $this->faker->randomFloat(2, 0, 10),
            'dewptf' => $this->faker->randomFloat(2, -40, 212),
            'humidity' => $this->faker->numberBetween(0, 100),
            'rainin' => $this->faker->randomFloat(2, 0, 10),
            'soilmoisture' => $this->faker->randomFloat(2, 0, 100),
            'soiltempf' => $this->faker->randomFloat(2, -40, 212),
            'tempf' => $tempf,
            'visibility' => $this->faker->randomFloat(2, 0, 10),
            'winddir' => $this->faker->numberBetween(0, 360),
            'windspeedmph' => $this->faker->randomFloat(2, 0, 100),
            'windgustmph' => $this->faker->randomFloat(2, 0, 100),
            'solarradiation' => $this->faker->randomFloat(2, 0, 1000),
        ]);

        $this
            ->actingAs($user)
            ->get('/api/v2/send?'.$query)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('id')
                ->has('longitude')
                ->has('latitude')
                ->has('altitude')
                ->has('baromin')
                ->has('absbaromin')
                ->has('dailyrainin')
                ->has('dewptf')
                ->has('humidity')
                ->has('rainin')
                ->has('soilmoisture')
                ->has('soiltempf')
                ->has('tempf')
                ->has('visibility')
                ->has('winddir')
                ->has('windspeedmph')
                ->has('windgustmph')
                ->has('solarradiation')
                ->has('created_at')
                ->has('updated_at')
                ->where('dateutc', $datetime->format(DATE_ATOM))
                ->where('softwaretype', $hash)
                ->where('site.id', $site->id)
                ->where('site_id', $site->id)
            );

        $this->assertAuthenticated();

        $this->assertDatabaseHas('observations', [
            'site_id' => $site->id,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $hash,
        ]);
    }

    public function test_send_observation_via_post(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $hash = $this->faker->sha256();
        $datetime = now()->utc();

        $data = [
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $hash,
        ];

        $this
            ->actingAs($user)
            ->post('/api/v2/send', $data)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('id')
                ->where('dateutc', $datetime->format(DATE_ATOM))
                ->where('softwaretype', $hash)
                ->where('site.id', $site->id)
                ->etc()
            );

        $this->assertAuthenticated();

        $this->assertDatabaseHas('observations', [
            'site_id' => $site->id,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $hash,
        ]);
    }

    public function test_datetime_format_validation(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $data = [
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'softwaretype' => $this->faker->word(),
        ];

        // Invalid datetime format
        $this
            ->actingAs($user)
            ->post('/api/v2/send', [...$data, 'dateutc' => 'invalid-datetime'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['dateutc']);

        // Valid URL-encoded datetime format (YYYY-MM-DD HH:MM:SS)
        $this
            ->actingAs($user)
            ->post('/api/v2/send', [...$data, 'dateutc' => urlencode('1970-01-01 01:01:01')])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('dateutc', '1970-01-01T01:01:01+00:00')
                ->etc()
            );

        // Valid datetime format (YYYY-M-D HH:MM:SS)
        $this
            ->actingAs($user)
            ->post('/api/v2/send', [...$data, 'dateutc' => '1970-1-1 01:01:01'])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('dateutc', '1970-01-01T01:01:01+00:00')
                ->etc()
            );

        // Valid datetime format (YYYY-MM-DD H:M:S)
        $this
            ->actingAs($user)
            ->post('/api/v2/send', [...$data, 'dateutc' => '1970-01-01 1:1:1'])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('dateutc', '1970-01-01T01:01:01+00:00')
                ->etc()
            );

        // Valid datetime format (YYYY-MM-DD H:M:S)
        $this
            ->actingAs($user)
            ->post('/api/v2/send', [...$data, 'dateutc' => '1970-01-01 1:1:1'])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('dateutc', '1970-01-01T01:01:01+00:00')
                ->etc()
            );

        // Valid datetime format (YYYY-M-D H:M:S)
        $this
            ->actingAs($user)
            ->post('/api/v2/send', [...$data, 'dateutc' => '1970-1-1 1:1:1'])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('dateutc', '1970-01-01T01:01:01+00:00')
                ->etc()
            );
    }

    public function test_send_observation_with_shortid(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $data = [
            'siteid' => $site->short_id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => now()->utc()->format('Y-m-d H:i:s'),
            'softwaretype' => $this->faker->word(),
        ];

        $this
            ->actingAs($user)
            ->post('/api/v2/send', $data)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('id')
                ->where('site.id', $site->id)
                ->etc()
            );

        $this->assertAuthenticated();

        $this->assertDatabaseHas('observations', [
            'site_id' => $site->id,
        ]);
    }

    public function test_pressure_absbaromin(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $datetime = now()->utc();
        $absbaromin = $this->faker->randomFloat(2, 28, 31);

        $data = [
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $this->faker->word(),
            'absbaromin' => $absbaromin,
        ];

        $this
            ->actingAs($user)
            ->post('/api/v2/send', $data)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('absbaromin')
                ->missing('baromin')
                ->etc()
            );

        $this->artisan('db:refresh-agg-5min')->execute();
        $this->artisan('db:refresh-agg-day')->execute();

        $this->assertDatabaseHas('observations_day_agg', [
            'site_id' => $site->id,
            'date' => $datetime->format('Y-m-d'),
            'avg_pressure' => round(1013.25 * ($absbaromin / 29.92), 2),
            'count' => 1,
        ]);

        $this->assertDatabaseHas('observations_5min_agg', [
            'site_id' => $site->id,
            'dateutc' => $datetime->copy()->setTime(
                $datetime->hour,
                floor($datetime->minute / 5) * 5,
                0
            )->format('Y-m-d H:i:s'),
            'pressure' => round(1013.25 * ($absbaromin / 29.92), 2),
            'count' => 1,
        ]);
    }

    public function test_pressure_baromin(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $datetime = now()->utc();
        $baromin = $this->faker->randomFloat(2, 28, 31);
        $tempf = $this->faker->randomFloat(2, -40, 212);

        $data = [
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $this->faker->word(),
            'baromin' => $baromin,
            'tempf' => $tempf,
        ];

        $this
            ->actingAs($user)
            ->post('/api/v2/send', $data)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('baromin')
                ->missing('absbaromin')
                ->etc()
            );

        $this->artisan('db:refresh-agg-5min')->execute();
        $this->artisan('db:refresh-agg-day')->execute();

        $this->assertDatabaseHas('observations_day_agg', [
            'site_id' => $site->id,
            'date' => $datetime->format('Y-m-d'),
            'avg_pressure' => round(1013.25 * (ObservationHelper::mslp($baromin, $tempf, $site->altitude) / 29.92), 2),
            'count' => 1,
        ]);

        $this->assertDatabaseHas('observations_5min_agg', [
            'site_id' => $site->id,
            'dateutc' => $datetime->copy()->setTime(
                $datetime->hour,
                floor($datetime->minute / 5) * 5,
                0
            )->format('Y-m-d H:i:s'),
            'pressure' => round(1013.25 * (ObservationHelper::mslp($baromin, $tempf, $site->altitude) / 29.92), 2),
            'count' => 1,
        ]);
    }
}
