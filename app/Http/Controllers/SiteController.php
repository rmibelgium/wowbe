<?php

namespace App\Http\Controllers;

use App\Http\Requests\Site\StoreRequest;
use App\Http\Requests\Site\UpdateRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class SiteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('site/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $site = new Site($request->validated());
        $site->user()->associate($request->user());
        $site->save();

        return to_route('dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site): InertiaResponse
    {
        Gate::authorize('update', $site);

        return Inertia::render('site/Edit', [
            'site' => $site,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Site $site): RedirectResponse
    {
        Gate::authorize('update', $site);

        $site->update($request->validated());

        return to_route('site.edit', ['site' => $site->id]);
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Site $site): InertiaResponse
    {
        Gate::authorize('delete', $site);

        return Inertia::render('site/Delete', [
            'site' => $site,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Site $site): RedirectResponse
    {
        Gate::authorize('delete', $site);

        $request->validate([
            'auth_key' => ['required', 'array', 'size:6'],
        ]);

        // Check if the provided auth_key matches the site's authentication key
        if (implode('', $request->input('auth_key')) !== $site->auth_key) {
            return back()->withErrors(['auth_key' => 'The authentication key is incorrect.']);
        }

        $site->delete();

        return to_route('dashboard');
    }
}
