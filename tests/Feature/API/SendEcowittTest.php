<?php

namespace Tests\Feature\API;

use App\Helpers\ObservationHelper;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SendEcowittTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_send_observation_via_post_global(): void
    {
        $macAddress = $this->faker->macAddress();

        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id, 'mac_address' => $macAddress]);

        $hash = $this->faker->sha256();
        $datetime = now()->utc();

        $absbaromin = $this->faker->randomFloat(2, 28, 31);
        $tempf = $this->faker->randomFloat(2, -40, 212);

        $data = [
            'PASSKEY' => strtoupper(md5($macAddress)),
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'stationtype' => $hash,
            'baromrelin' => ObservationHelper::absbaromin2baromin($absbaromin, $tempf, $site->altitude),
            'baromabsin' => $absbaromin,
            'tempf' => $tempf,
            'humidity' => $this->faker->numberBetween(0, 100),
            'winddir' => $this->faker->numberBetween(0, 360),
            'windspeedmph' => $this->faker->randomFloat(2, 0, 100),
            'windgustmph' => $this->faker->randomFloat(2, 0, 100),
            'rainratein' => $this->faker->randomFloat(2, 0, 10),
            'dailyrainin' => $this->faker->randomFloat(2, 0, 10),
            'solarradiation' => $this->faker->randomFloat(2, 0, 1000),
            'model' => $this->faker->word(),
        ];

        $this
            ->post('/api/v2/send', $data)
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
                ->has('properties.humidity')
                ->has('properties.rainin')
                ->has('properties.tempf')
                ->has('properties.winddir')
                ->has('properties.windspeedmph')
                ->has('properties.windgustmph')
                ->has('properties.solarradiation')
                ->has('properties.model')
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
        $macAddress = $this->faker->macAddress();

        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id, 'mac_address' => $macAddress]);

        $hash = $this->faker->sha256();
        $datetime = now()->utc();

        $absbaromin = $this->faker->randomFloat(2, 28, 31);
        $tempf = $this->faker->randomFloat(2, -40, 212);

        $data = [
            'PASSKEY' => strtoupper(md5($macAddress)),
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'stationtype' => $hash,
            'baromrelin' => ObservationHelper::absbaromin2baromin($absbaromin, $tempf, $site->altitude),
            'baromabsin' => $absbaromin,
            'tempf' => $tempf,
            'humidity' => $this->faker->numberBetween(0, 100),
            'winddir' => $this->faker->numberBetween(0, 360),
            'windspeedmph' => $this->faker->randomFloat(2, 0, 100),
            'windgustmph' => $this->faker->randomFloat(2, 0, 100),
            'rainratein' => $this->faker->randomFloat(2, 0, 10),
            'dailyrainin' => $this->faker->randomFloat(2, 0, 10),
            'solarradiation' => $this->faker->randomFloat(2, 0, 1000),
            'model' => $this->faker->word(),
        ];

        $this
            ->post('/api/v2/send/ecowitt', $data)
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
                ->has('properties.humidity')
                ->has('properties.rainin')
                ->has('properties.tempf')
                ->has('properties.winddir')
                ->has('properties.windspeedmph')
                ->has('properties.windgustmph')
                ->has('properties.solarradiation')
                ->has('properties.model')
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

    public function test_authentication_failure_invalid_passkey(): void
    {
        $data = [
            'PASSKEY' => 'invalid_passkey_hash',
            'dateutc' => now()->utc()->format('Y-m-d H:i:s'),
            'stationtype' => $this->faker->sha256(),
            'tempf' => $this->faker->randomFloat(2, -40, 212),
        ];

        $this
            ->post('/api/v2/send', $data)
            ->assertStatus(404);

        $this->assertDatabaseMissing('observations', []);
    }
}
