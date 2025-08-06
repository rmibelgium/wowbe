<?php

namespace Tests\Unit;

use App\Http\Middleware\Localization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    public function test_sets_locale_to_en_when_accept_language_is_en()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'en',
        ]);

        (new Localization)->handle($request, fn () => new Response);

        $this->assertTrue(App::isLocale('en'));
    }

    public function test_sets_locale_to_fr_when_accept_language_contains_fr()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'fr,fr-BE;q=0.8,en-US;q=0.5,en;q=0.3',
        ]);

        (new Localization)->handle($request, fn () => new Response);

        $this->assertTrue(App::isLocale('fr'));
    }

    public function test_sets_locale_to_nl_when_accept_language_is_nl()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'nl',
        ]);

        (new Localization)->handle($request, fn () => new Response);

        $this->assertTrue(App::isLocale('nl'));
    }

    public function test_does_not_set_locale_when_accept_language_is_not_supported()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'es',
        ]);

        (new Localization)->handle($request, fn () => new Response);

        $this->assertTrue(App::isLocale(config('app.locale')));
    }

    public function test_sets_locale_from_query_parameter()
    {
        $request = Request::create('/?lang=fr', 'GET');

        (new Localization)->handle($request, fn () => new Response);

        $this->assertTrue(App::isLocale('fr'));
    }
}
