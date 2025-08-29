<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Localization;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'availableLocales' => Localization::getAvailableLocales(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): HttpFoundationResponse|RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $mustReload = $request->user()->isDirty('locale');

        $request->user()->save();

        return $mustReload === true ? Inertia::location(route('profile.edit')) : to_route('profile.edit');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (is_null($user->oauth_provider)) {
            $request->validate([
                'delete_data' => ['boolean'],
                'password' => ['required', 'current_password'],
            ]);
        } else {
            $request->validate([
                'delete_data' => ['boolean'],
            ]);
        }

        Auth::logout();

        if ($request->has('delete_data') === true && $request->input('delete_data') === true) {
            $user->sites()->each(function ($site) {
                $site->forceDelete();
            });
        } else {
            $user->sites()->each(function ($site) {
                $site->delete();
            });
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
