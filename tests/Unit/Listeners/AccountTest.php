<?php

namespace Tests\Unit\Listeners;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_listeners_are_attached(): void
    {
        Event::fake();

        Event::assertListening(\App\Events\AccountCreated::class, \App\Listeners\AccountCreated::class);
        Event::assertListening(\App\Events\AccountDeleted::class, \App\Listeners\AccountDeleted::class);
    }

    public function test_created_listener(): void
    {
        Mail::fake();

        $user = User::factory()->createOne();

        $event = new \App\Events\AccountCreated($user);
        $listener = new \App\Listeners\AccountCreated;
        $listener->handle($event);

        Mail::assertSent(\App\Mail\AccountCreated::class);
    }

    public function test_deleted_listener(): void
    {
        Mail::fake();

        $user = User::factory()->createOne();

        $event = new \App\Events\AccountDeleted($user);
        $listener = new \App\Listeners\AccountDeleted;
        $listener->handle($event);

        Mail::assertSent(\App\Mail\AccountDeleted::class);
    }
}
