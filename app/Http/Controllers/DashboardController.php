<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $sites = $request->user()
            ->sites()
            ->withAggregate('observations', new Expression('MAX(dateutc)'))
            ->withAggregate('observations', new Expression('COUNT(*)'))
            ->get();

        return Inertia::render('Dashboard', [
            'sites' => $sites,
        ]);
    }
}
