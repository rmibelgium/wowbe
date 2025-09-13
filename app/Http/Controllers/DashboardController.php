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
        $query = $request->user()
            ->sites()
            ->withAggregate('observations', new Expression('MAX(dateutc)'))
            ->withAggregate('observations', new Expression('COUNT(*)'));

        // Handle sorting
        $sortBy = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction', 'asc');

        if (! is_null($sortBy)) {
            $query->orderBy($sortBy === 'name' ? new Expression("LOWER($sortBy)") : $sortBy, $sortDirection);
        } else {
            // Default sorting
            $query->orderBy('created_at', 'desc');
        }

        // Add pagination
        $sites = $query->paginate();

        // Append query parameters to pagination links
        $sites->appends($request->query());

        return Inertia::render('Dashboard', [
            'sites' => $sites->items(),
            'pagination' => [
                'current_page' => $sites->currentPage(),
                'last_page' => $sites->lastPage(),
                'per_page' => $sites->perPage(),
                'total' => $sites->total(),
                'from' => $sites->firstItem(),
                'to' => $sites->lastItem(),
                'has_more_pages' => $sites->hasMorePages(),
                'links' => $sites->linkCollection(),
            ],
            'filters' => [
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
            ],
        ]);
    }
}
