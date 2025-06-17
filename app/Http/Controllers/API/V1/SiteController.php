<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ObservationHelper;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Observation;
use App\Models\Site;
use Illuminate\Http\JsonResponse;

class SiteController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Site $site): JsonResponse
    {
        $latest = $site->latest->first();

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
            'isOfficial' => null,
            'metOfficeId' => null,
            'location' => [
                'geography' => [
                    'coordinateSystemId' => 4326,
                    'wellKnownText' => sprintf(
                        'POINT (%.6f %.6f %.6f)',
                        $site->longitude,
                        $site->latitude,
                        $site->height
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
            'lastObservationDate' => isset($latest) ? ObservationHelper::serializeDateUTC($latest) : null,
            'hasWebcams' => false,
            'hasMagnetometers' => false,
            'hasMagnetometerLicenceRequirement' => false,
            'timeZoneStandard' => config('app.timezone'),
        ];

        return response()->json($result);
    }

    public function latest(Site $site): JsonResponse
    {
        $latest = $site->latest->first();

        $result = [
            'geometry' => SiteHelper::serializeGeometry($site, false),
            'timestamp' => isset($latest) ? ObservationHelper::serializeDateUTC($latest) : null,
            'primary' => [
                'dt' => $latest?->tempf,
                'dws' => $latest?->windspeedmph,
                'dwd' => $latest?->winddir,
                'drr' => $latest?->rainin,
                'dm' => $latest?->baromin,
                'dh' => $latest?->humidity,
            ],
        ];

        return response()->json($result);
    }

    public function graph(Site $site): JsonResponse
    {
        $observations = $site->observations()
            ->whereDate('dateutc', '>', now()->subHours(24))
            ->orderBy('dateutc')
            ->get();

        $result = $observations->map(fn (Observation $observation) => [
            'timestamp' => ObservationHelper::serializeDateUTC($observation),
            'primary' => [
                'dt' => $observation->tempf,
                'dws' => $observation->windspeedmph,
                'dwd' => $observation->winddir,
                'drr' => $observation->rainin,
                'dm' => $observation->baromin,
                'dh' => $observation->humidity,
            ],
        ]);

        return response()->json($result);
    }
}
