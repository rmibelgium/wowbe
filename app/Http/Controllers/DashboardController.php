<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $sites = $request->user()
            ->sites()
            ->with('observations')
            ->get();

        return Inertia::render('Dashboard', [
            'sites' => $sites,
        ]);
    }
}
