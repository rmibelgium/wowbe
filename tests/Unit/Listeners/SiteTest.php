<?php

namespace Tests\Unit\Listeners;

use App\Models\Observation;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_listeners_are_attached(): void
    {
        Event::fake();

        Event::assertListening(\App\Events\SiteCreated::class, \App\Listeners\SiteCreated::class);
        Event::assertListening(\App\Events\SiteDeleted::class, \App\Listeners\SiteDeleted::class);
    }

    public function test_created_listener(): void
    {
        Mail::fake();

        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $event = new \App\Events\SiteCreated($site);
        $listener = new \App\Listeners\SiteCreated;
        $listener->handle($event);

        Mail::assertSent(\App\Mail\SiteCreated::class);
    }

    public function test_deleted_listener(): void
    {
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        Observation::factory()->count(3)->create(['site_id' => $site->id]);

        $event = new \App\Events\SiteDeleted($site);
        $listener = new \App\Listeners\SiteDeleted;
        $listener->handle($event);

        $this->assertSoftDeleted('observations', [
            'site_id' => $site->id,
        ]);
    }
}
