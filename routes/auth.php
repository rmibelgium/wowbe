<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Socialite

Route::get('auth/{provider}/redirect', function (string $provider) {
    return Socialite::driver($provider)->redirect();
})->whereIn('provider', config('auth.oauth_providers'))->name('oauth.redirect');

Route::get('auth/{provider}/callback', function (string $provider) {
    $oauthUser = Socialite::driver($provider)->user();

    $user = User::updateOrCreate([
        'oauth_provider' => $provider,
        'oauth_id' => $oauthUser->id,
    ], [
        'name' => $oauthUser->name,
        'email' => $oauthUser->email,
        'email_verified_at' => now(),
        'oauth_provider' => $provider,
        'oauth_id' => $oauthUser->id,
        'avatar' => $oauthUser->avatar ?? null,
    ]);

    Auth::login($user);

    return to_route('dashboard');
})->whereIn('provider', config('auth.oauth_providers'))->name('oauth.callback');
