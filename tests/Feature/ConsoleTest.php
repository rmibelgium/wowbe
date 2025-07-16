<?php

namespace Tests\Feature;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ConsoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_random_observations_command(): void
    {
        $user = User::factory()->createOne();
        $site = Site::factory()->createOne(['user_id' => $user->id]);

        $this->artisan('app:random-observations')
            ->expectsOutput('Processing site: '.$site->name)
            ->assertSuccessful();

        $this->assertDatabaseCount('observations', 1);
    }

    public function test_test_email_command(): void
    {
        $user = User::factory()->createOne();
        Site::factory()->createOne(['user_id' => $user->id]);

        Mail::fake();

        $this->artisan('app:test-email')
            ->expectsQuestion('Please enter the recipient email address for the test email(s):', 'test@example.com')
            ->expectsQuestion('Which email do you want to send?', [
                \App\Mail\AccountCreated::class,
                \App\Mail\AccountDeleted::class,
                \App\Mail\SiteCreated::class,
            ])
            ->assertSuccessful();

        Mail::assertSent(\App\Mail\AccountCreated::class, 'test@example.com');
        Mail::assertSent(\App\Mail\AccountDeleted::class, 'test@example.com');
        Mail::assertSent(\App\Mail\SiteCreated::class, 'test@example.com');

        Mail::assertSentCount(3);
    }
}
