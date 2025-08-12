<?php

namespace Tests\Unit;

use App\Http\Middleware\Localization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_created_email(): void
    {
        $user = \App\Models\User::factory()->createOne();

        foreach (Localization::LOCALES as $locale) {
            new \App\Mail\AccountCreated($user)
                ->locale($locale)
                ->assertHasSubject(config('app.name').' - '.trans('mail.account_created.subject', [], $locale));
        }
    }

    public function test_account_deleted_email(): void
    {
        $user = \App\Models\User::factory()->createOne();

        foreach (Localization::LOCALES as $locale) {
            new \App\Mail\AccountDeleted($user)
                ->locale($locale)
                ->assertHasSubject(config('app.name').' - '.trans('mail.account_deleted.subject', [], $locale));
        }
    }

    public function test_site_created_email(): void
    {
        $user = \App\Models\User::factory()->createOne();
        $site = \App\Models\Site::factory()->createOne(['user_id' => $user->id]);

        foreach (Localization::LOCALES as $locale) {
            new \App\Mail\SiteCreated($site)
                ->locale($locale)
                ->assertHasSubject(config('app.name').' - '.trans('mail.site_created.subject', [], $locale))
                ->assertSeeInOrderInHtml([$site->name, $site->id, $site->auth_key])
                ->assertSeeInOrderInText([$site->name, $site->id, $site->auth_key]);
        }
    }
}
