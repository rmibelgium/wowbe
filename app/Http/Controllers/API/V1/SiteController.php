<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\DayAggregate;
use App\Models\FiveMinutesAggregate;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the site.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['string', 'min:2'],
        ]);

        // If a search term is provided, filter the sites by name.
        // Otherwise, retrieve all sites.
        if (isset($validated['search'])) {
            $sites = Site::whereLike('name', '%'.$validated['search'].'%', false)->get();
        } else {
            $sites = Site::all();
        }

        return response()->json($sites);
    }

    /**
     * Display the specified site.
     */
    public function show(Site $site): JsonResponse
    {
        $latest = $site->fiveMinutesAggregate()
            ->latest('datetime')
            ->first();

        $result = [
            'id' => $site->id,
            'siteName' => $site->name,
            'groupId' => null,
            'groupName' => null,
            'groupTypeName' => null,
            'groupImageUrl' => null,
            'belongsToBrand' => null,
            'hasGroup' => false,
            'isSchool' => false,
            'isOfficial' => $site->is_official,
            'metOfficeId' => null,
            'location' => [
                'geography' => [
                    'coordinateSystemId' => 4326,
                    'wellKnownText' => sprintf(
                        'POINT (%.6f %.6f %.6f)',
                        $site->longitude,
                        $site->latitude,
                        $site->altitude
                    ),
                ],
            ],
            'siteLogoImagePath' => null,
            'northSideImagePath' => null,
            'eastSideImagePath' => null,
            'southSideImagePath' => null,
            'westSideImagePath' => null,
            'siteRating' => null,
            'description' => null,
            'isActive' => null,
            'allowDownload' => false,
            'reason' => null,
            'timeZone' => $site->timezone,
            'locationExposureAttrib' => null,
            'temperatureAttrib' => null,
            'rainfallAttrib' => null,
            'windAttrib' => null,
            'urbanClimateZoneAttrib' => null,
            'reportingHoursAttrib' => null,
            'website' => null,
            'otherReason' => null,
            'equipmentType' => null,
            'defaultGraphFields' => null,
            'defaultTableFields' => null,
            'isDcnn' => null,
            'hasEditPermission' => false,
            'hasViewHolidaysPermission' => false,
            'hasManageUsersPermission' => false,
            'latestBadge' => [
                'id' => null,
                'year' => null,
                'siteId' => null,
                'type' => null,
                'operationalSince' => null,
            ],
            'isReported' => false,
            'ownerId' => $site->user->id,
            'lastObservationDate' => $latest?->datetime->format(DATE_ATOM),
            'hasWebcams' => false,
            'hasMagnetometers' => false,
            'hasMagnetometerLicenceRequirement' => false,
            'timeZoneStandard' => config('app.timezone'),
        ];

        return response()->json($result);
    }

    /**
     * Get the latest observations for a specific site.
     */
    public function latest(Site $site): JsonResponse
    {
        $latest = $site->fiveMinutesAggregate()
            // ->whereDate('datetime', '>', now()->utc()->subHours(24))
            ->latest('datetime')
            ->first();

        $result = [
            'geometry' => SiteHelper::serializeGeometry($latest->site, false),
            'timestamp' => $latest?->datetime->format(DATE_ATOM),
            'primary' => [
                'dt' => $latest?->temperature,
                'dws' => $latest?->windspeed,
                'dwd' => $latest?->winddir,
                'drr' => null, // TODO
                'dm' => $latest?->pressure,
                'dh' => $latest?->humidity,
            ],
        ];

        return response()->json($result);
    }

    /**
     * Get the graph data for a specific site.
     */
    public function graph(Site $site): JsonResponse
    {
        $observations = $site->fiveMinutesAggregate()
            ->whereDate('datetime', '>', now()->utc()->subHours(24))
            ->orderBy('datetime')
            ->get();

        $result = $observations->map(fn (FiveMinutesAggregate $observation) => [
            'timestamp' => $observation->datetime->format(DATE_ATOM),
            'primary' => [
                'dt' => $observation->temperature,
                'dws' => $observation->windspeed,
                'dwd' => $observation->winddir,
                'drr' => null, // TODO,
                'dm' => $observation->pressure,
                'dh' => $observation->humidity,
            ],
        ]);

        return response()->json($result);
    }

    /**
     * Get the daily summaries for a specific site.
     */
    public function daily(Site $site): JsonResponse
    {
        $dailySummaries = $site->dayAggregate()
            ->orderBy('date')
            ->get();

        $result = $dailySummaries->map(fn (DayAggregate $agg): array => [
            'date' => $agg->date->format(DATE_ATOM),
            'data' => [
                'temperature' => [
                    'min' => $agg->min_temperature,
                    'max' => $agg->max_temperature,
                    'mean' => $agg->avg_temperature,
                ],
                'dewpoint' => [
                    'mean' => $agg->avg_dewpoint,
                ],
                'humidity' => [
                    'mean' => $agg->avg_humidity,
                ],
                'rainfall' => [
                    'max_intensity' => null, // TODO
                    'precipitation_quantity' => $agg->max_dailyrainin,
                    'precipitation_duration' => null, // TODO
                ],
                'wind' => [
                    'max' => $agg->max_windspeed,
                    'gust' => $agg->max_windgustspeed,
                ],
                'pressure' => [
                    'mean' => $agg->avg_pressure,
                ],
            ],
        ]);

        return response()->json($result);
    }
}
