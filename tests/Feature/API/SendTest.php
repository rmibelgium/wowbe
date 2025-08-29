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
            ->get('/api/v2/send?'.$query)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('type', 'Feature')
                ->has('id')
                ->where('geometry.type', 'Point')
                ->has('geometry.coordinates.0')
                ->has('geometry.coordinates.1')
                ->has('geometry.coordinates.2')
                ->has('properties.baromin')
                ->has('properties.absbaromin')
                ->has('properties.dailyrainin')
                ->has('properties.dewptf')
                ->has('properties.humidity')
                ->has('properties.rainin')
                ->has('properties.soilmoisture')
                ->has('properties.soiltempf')
                ->has('properties.tempf')
                ->has('properties.visibility')
                ->has('properties.winddir')
                ->has('properties.windspeedmph')
                ->has('properties.windgustmph')
                ->has('properties.solarradiation')
                ->has('metadata.created_at')
                ->has('metadata.updated_at')
                ->where('properties.dateutc', $datetime->format(DATE_ATOM))
                ->where('properties.softwaretype', $hash)
                ->where('properties.site.id', $site->id)
            );

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
            ->post('/api/v2/send', $data)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('id')
                ->where('properties.dateutc', $datetime->format(DATE_ATOM))
                ->where('properties.softwaretype', $hash)
                ->where('properties.site.id', $site->id)
                ->etc()
            );

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
            ->post('/api/v2/send', [...$data, 'dateutc' => 'invalid-datetime'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['dateutc']);

        // Valid URL-encoded datetime format (YYYY-MM-DD HH:MM:SS)
        $this
            ->post('/api/v2/send', [...$data, 'dateutc' => urlencode('1970-01-01 01:01:01')])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('properties.dateutc', '1970-01-01T01:01:01+00:00')
                ->etc()
            );

        // Valid datetime format (YYYY-M-D HH:MM:SS)
        $this
            ->post('/api/v2/send', [...$data, 'dateutc' => '1970-1-1 01:01:01'])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('properties.dateutc', '1970-01-01T01:01:01+00:00')
                ->etc()
            );

        // Valid datetime format (YYYY-MM-DD H:M:S)
        $this
            ->post('/api/v2/send', [...$data, 'dateutc' => '1970-01-01 1:1:1'])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('properties.dateutc', '1970-01-01T01:01:01+00:00')
                ->etc()
            );

        // Valid datetime format (YYYY-MM-DD H:M:S)
        $this
            ->post('/api/v2/send', [...$data, 'dateutc' => '1970-01-01 1:1:1'])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('properties.dateutc', '1970-01-01T01:01:01+00:00')
                ->etc()
            );

        // Valid datetime format (YYYY-M-D H:M:S)
        $this
            ->post('/api/v2/send', [...$data, 'dateutc' => '1970-1-1 1:1:1'])
            ->assertOk()
            ->assertJsonMissingValidationErrors()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('properties.dateutc', '1970-01-01T01:01:01+00:00')
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
            ->post('/api/v2/send', $data)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('id')
                ->where('properties.site.id', $site->id)
                ->etc()
            );

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
            ->post('/api/v2/send', $data)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('properties.absbaromin')
                ->missing('properties.baromin')
                ->etc()
            );

        $this->assertDatabaseHas('observations_agg_day', [
            'site_id' => $site->id,
            'date' => $datetime->format('Y-m-d'),
            'avg_pressure' => round(1013.25 * ($absbaromin / 29.92), 2),
            'count' => 1,
        ]);

        $this->assertDatabaseHas('observations_agg_5min', [
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
            ->post('/api/v2/send', $data)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('properties.baromin')
                ->missing('properties.absbaromin')
                ->etc()
            );

        $this->assertDatabaseHas('observations_agg_day', [
            'site_id' => $site->id,
            'date' => $datetime->format('Y-m-d'),
            'avg_pressure' => round(1013.25 * (ObservationHelper::mslp($baromin, $tempf, $site->altitude) / 29.92), 2),
            'count' => 1,
        ]);

        $this->assertDatabaseHas('observations_agg_5min', [
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

    public function test_authentication_failure_invalid_site_id(): void
    {
        $data = [
            'siteid' => 'non-existent-site-id',
            'siteAuthenticationKey' => 'some_auth_key',
            'dateutc' => now()->utc()->format('Y-m-d H:i:s'),
            'softwaretype' => $this->faker->word(),
        ];

        $this
            ->post('/api/v2/send', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['siteid']);

        $this->assertDatabaseMissing('observations');
    }

    public function test_authentication_failure_invalid_auth_key(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $data = [
            'siteid' => $site->id,
            'siteAuthenticationKey' => 'invalid_auth_key',
            'dateutc' => now()->utc()->format('Y-m-d H:i:s'),
            'softwaretype' => $this->faker->word(),
        ];

        $this
            ->post('/api/v2/send', $data)
            ->assertStatus(403);

        $this->assertDatabaseMissing('observations', [
            'site_id' => $site->id,
        ]);
    }

    public function test_datetime_decode_handles_invalid_datetime(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        // Test with an invalid datetime format that strtotime can't parse
        $data = [
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => 'invalid-datetime-format',
            'softwaretype' => $this->faker->word(),
            'tempf' => 75.5,
        ];

        $this
            ->post('/api/v2/send', $data)
            ->assertStatus(422);

        $this->assertDatabaseMissing('observations', [
            'site_id' => $site->id,
        ]);
    }

    public function test_datetime_decode_handles_null_datetime(): void
    {
        $macAddress = $this->faker->macAddress();

        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id, 'mac_address' => $macAddress]);

        $data = [
            'PASSKEY' => strtoupper(md5($macAddress)),
            'tempf' => 75.5,
        ];

        $this
            ->post('/api/v2/send', $data)
            ->assertJsonValidationErrorFor('dateutc');
    }

    public function test_datetime_decode_handles_now_datetime(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        // Test with 'now' dateutc (this should be handled by the decodeDateTime method)
        $data = [
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => 'now',
            'softwaretype' => $this->faker->word(),
            'tempf' => 75.5,
        ];

        $this
            ->post('/api/v2/send', $data)
            ->assertOk();

        $this->assertDatabaseHas('observations', [
            'site_id' => $site->id,
        ]);
    }

    public function test_datetime_decode_handles_url_encoded_datetime(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $datetime = now()->utc();
        // URL encode the datetime
        $encodedDateTime = urlencode($datetime->format('Y-m-d H:i:s'));

        $data = [
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => $encodedDateTime,
            'softwaretype' => $this->faker->word(),
            'tempf' => 75.5,
        ];

        $this
            ->post('/api/v2/send', $data)
            ->assertOk();

        $this->assertDatabaseHas('observations', [
            'site_id' => $site->id,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
        ]);
    }
}
