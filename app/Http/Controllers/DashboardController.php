<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $sites = Site::where('user_id', $user->id)
            ->with('latest')
            ->get();

        return Inertia::render('Dashboard', [
            'sites' => $sites,
        ]);
    }
}
