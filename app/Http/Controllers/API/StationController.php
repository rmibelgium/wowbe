<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class StationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function metadata(Request $request, string $id): JsonResponse
    {
        $uuid = Uuid::fromString($id);

        dd($request->format());

        return response()->json(json_decode('{"id":"e6a6eb11-690e-ed11-b5cf-0003ff59a71f","siteName":"GM45 Station - Carlsbourg","groupId":null,"groupName":null,"groupTypeName":null,"groupImageUrl":null,"belongsToBrand":null,"hasGroup":false,"isSchool":false,"isOfficial":null,"metOfficeId":null,"location":{"geography":{"coordinateSystemId":4326,"wellKnownText":"POINT (5.0824 49.8945 420)"}},"siteLogoImagePath":"https:\/\/mowowprod.blob.core.windows.net\/images\/bf3057c8-c7b5-ed11-9ac4-201642ba4e0a\/station.jpg","northSideImagePath":null,"eastSideImagePath":null,"southSideImagePath":null,"westSideImagePath":null,"siteRating":0,"description":"Automatic station EcoWitt WittBoy GW2001.\r\nBackground Radiation Data and Local Meteo.","isActive":true,"allowDownload":true,"reason":"Education","timeZone":"Romance Standard Time","locationExposureAttrib":null,"temperatureAttrib":null,"rainfallAttrib":null,"windAttrib":null,"urbanClimateZoneAttrib":null,"reportingHoursAttrib":null,"website":"https:\/\/jma0800.net","otherReason":null,"equipmentType":0,"defaultGraphFields":null,"defaultTableFields":null,"isDcnn":false,"hasEditPermission":false,"hasViewHolidaysPermission":false,"hasManageUsersPermission":false,"latestBadge":{"id":null,"year":2024,"siteId":null,"type":3,"operationalSince":null},"isReported":false,"ownerId":"eecfb7fe-470e-ed11-b5cf-0003ff597f35","lastObservationDate":"2025-03-13T19:10:45Z","hasWebcams":false,"hasMagnetometers":false,"hasMagnetometerLicenceRequirement":false,"timeZoneStandard":"Europe\/Paris"}'));
    }

    /**
     * Handle the incoming request.
     */
    public function latest(Request $request, string $id): JsonResponse
    {
        $uuid = Uuid::fromString($id);

        return response()->json(json_decode('{"geometry":{"type":"Point","coordinates":[5.0824,49.8945]},"timestamp":"2025-03-13T19:10:45+00:00","primary":{"dt":1.1,"dws":2.53,"dwd":310,"drr":null,"dm":1001.2,"dh":83}}'));
    }

    /**
     * Handle the incoming request.
     */
    public function daily(Request $request, string $id): JsonResponse
    {
        $uuid = Uuid::fromString($id);
    }

    /**
     * Handle the incoming request.
     */
    public function graph(Request $request, string $id): JsonResponse
    {
        $uuid = Uuid::fromString($id);
    }
}
