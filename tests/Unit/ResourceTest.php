<?php

namespace Tests\Unit;

use App\Http\Resources\SiteCollection;
use App\Http\Resources\SiteResource;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_resource_transforms_site_correctly(): void
    {
        $user = User::factory()->create();
        $site = Site::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Site',
            'latitude' => 50.8503,
            'longitude' => 4.3517,
            'altitude' => 100,
        ]);

        $request = Request::create('/test');
        $resource = new SiteResource($site);

        $result = $resource->toArray($request);

        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('Feature', $result['type']);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($site->id, $result['id']);
        $this->assertArrayHasKey('geometry', $result);
        $this->assertArrayHasKey('properties', $result);
        $this->assertArrayHasKey('pictures', $result['properties']);
    }

    public function test_site_collection_transforms_collection_correctly(): void
    {
        $user = User::factory()->create();
        $sites = Site::factory()->count(3)->create(['user_id' => $user->id]);

        $request = Request::create('/test');
        $collection = new SiteCollection($sites);

        $result = $collection->toArray($request);

        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('FeatureCollection', $result['type']);
        $this->assertArrayHasKey('features', $result);
        $this->assertCount(3, $result['features']);
    }
}
