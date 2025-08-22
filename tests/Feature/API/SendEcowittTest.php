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

    public function test_send_observation_via_post(): void
    {
        $macAddress = $this->faker->macAddress();

        /** @var User $user */
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id, 'mac_address' => $macAddress]);

        $hash = $this->faker->sha256();
        $datetime = now()->utc();

        $baromin = $this->faker->randomFloat(2, 28, 31);
        $tempf = $this->faker->randomFloat(2, -40, 212);

        $query = http_build_query([
            'passkey' => md5($macAddress),
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'stationtype' => $hash,
            'baromrelin' => $baromin,
            'baromabsin' => ObservationHelper::mslp($baromin, $tempf, $site->altitude),
            'tempf' => $tempf,
            'humidity' => $this->faker->numberBetween(0, 100),
            'winddir' => $this->faker->numberBetween(0, 360),
            'windspeedmph' => $this->faker->randomFloat(2, 0, 100),
            'windgustmph' => $this->faker->randomFloat(2, 0, 100),
            'rainratein' => $this->faker->randomFloat(2, 0, 10),
            'dailyrainin' => $this->faker->randomFloat(2, 0, 10),
            'solarradiation' => $this->faker->randomFloat(2, 0, 1000),
            'model' => $this->faker->word(),
        ]);

        $this
            ->actingAs($user)
            ->get('/api/v2/send/ecowitt?'.$query)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('id')
                ->has('longitude')
                ->has('latitude')
                ->has('altitude')
                ->has('baromin')
                ->has('absbaromin')
                ->has('dailyrainin')
                ->has('humidity')
                ->has('rainin')
                ->has('tempf')
                ->has('winddir')
                ->has('windspeedmph')
                ->has('windgustmph')
                ->has('solarradiation')
                ->has('model')
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
}
