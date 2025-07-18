<?php

namespace Tests\Feature\API;

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
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $hash = $this->faker->sha256();
        $datetime = now()->utc();

        $query = http_build_query([
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $hash,
            'temperature' => $this->faker->randomFloat(2, -40, 212),
        ]);

        $this
            ->actingAs($user)
            ->get('/api/v2/send?'.$query)
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($datetime, $hash, $site) {
                return $json
                    ->has('id')
                    ->where('dateutc', $datetime->format(DATE_ATOM))
                    ->where('softwaretype', $hash)
                    ->where('site.id', $site->id)
                    ->etc();
            });

        $this->assertAuthenticated();

        $this->assertDatabaseHas('observations', [
            'site_id' => $site->id,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $hash,
        ]);
    }

    public function test_send_observation_via_post(): void
    {
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $hash = $this->faker->sha256();
        $datetime = now()->utc();

        $data = [
            'siteid' => $site->id,
            'siteAuthenticationKey' => $site->auth_key,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $hash,
            'temperature' => $this->faker->randomFloat(2, -40, 212),
        ];

        $this
            ->actingAs($user)
            ->post('/api/v2/send', $data)
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($datetime, $hash, $site) {
                return $json
                    ->has('id')
                    ->where('dateutc', $datetime->format(DATE_ATOM))
                    ->where('softwaretype', $hash)
                    ->where('site.id', $site->id)
                    ->etc();
            });

        $this->assertAuthenticated();

        $this->assertDatabaseHas('observations', [
            'site_id' => $site->id,
            'dateutc' => $datetime->format('Y-m-d H:i:s'),
            'softwaretype' => $hash,
        ]);
    }
}
