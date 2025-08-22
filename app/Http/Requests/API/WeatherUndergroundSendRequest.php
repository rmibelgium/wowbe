<?php

namespace App\Http\Requests\API;

class WeatherUndergroundSendRequest extends SendRequest
{
    /**
     * Extract the Site ID from the request.
     */
    public function extractSiteID(): ?string
    {
        return $this->input('ID');
    }

    /**
     * Extract the Authentication Key from the request.
     */
    public function extractAuthKey(): ?string
    {
        return $this->input('PASSWORD');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @see https://support.weather.com/s/article/PWS-Upload-Protocol
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Action (supposed to be `action=updateraw`)
            'action' => ['string'],
            // Site ID
            'ID' => ['required', 'string', new \App\Rules\SiteID],
            // Authentication Key (PIN code or Password)
            'PASSWORD' => ['required', 'string'],
            // Date & Time in UTC
            'dateutc' => ['required', 'date_format:Y-m-d H:i:s'],
            // Instantaneous Wind Direction (Degrees (0-360))
            'winddir' => ['numeric'],
            // Instantaneous Wind Speed (Miles per Hour)
            'windspeedmph' => ['numeric'],
            // Current Wind Gust Speed (using software specific time period) (Miles per Hour)
            'windgustmph' => ['numeric'],
            // Current Wind Gust Direction (using software specific time period) (Degrees (0-360))
            'windgustdir' => ['numeric'],
            // Outdoor Humidity (0-100 %)
            'humidity' => ['numeric'],
            // Outdoor Dewpoint (Fahrenheit)
            'dewptf' => ['numeric'],
            // Outdoor Temperature (Fahrenheit)
            'tempf' => ['numeric'],
            // Accumulated rainfall since the previous observation (Inches)
            'rainin' => ['numeric'],
            // Daily Accumulated rainfall so far today (Inches)
            'dailyrainin' => ['numeric'],
            // Relative Barometric Pressure (at site location) (Inch of Mercury)
            'baromin' => ['numeric'],
            // Soil Temperature (10cm) (Fahrenheit)
            'soiltempf' => ['numeric'],
            // % Moisture (0-100 %)
            'soilmoisture' => ['numeric'],
            // Solar Radiation (Watt per Square Metre)
            'solarradiation' => ['numeric'],
            // UV Index
            'UV' => ['numeric'],
            // Visibility (Kilometres)
            'visibility' => ['numeric'],
            // Software Type
            'softwaretype' => ['string'],

            /**
             * Following parameters are NOT part of WeatherUnderground protocol but are supported by WOW protocol.
             * Those parameters WILL be stored in WOW-BE.
             */

            // Absolute Barometric Pressure - Mean Sea Level Pressure (MSLP) (Inch of Mercury)
            'absbaromin' => ['numeric'],

            /**
             * Following parameters are part of WeatherUnderground protocol but are NOT supported by WOW protocol.
             * Those parameters WON'T be stored in WOW-BE.
             */

            // mph 2 minute average wind speed mph
            'windspdmph_avg2m' => ['numeric'],
            // 0-360 2 minute average wind direction
            'winddir_avg2m' => ['numeric'],
            // mph past 10 minutes wind gust mph
            'windgustmph_10m' => ['numeric'],
            // 0-360 past 10 minutes wind gust direction
            'windgustdir_10m' => ['numeric'],
            // metar style (+RA)
            'weather' => ['string'],
            // SKC, FEW, SCT, BKN, OVC
            'clouds' => ['string'],
            // Leaf Wetness (0-100 %)
            'leafwetness' => ['numeric'],
            // Indoor Temperature (Fahrenheit)
            'indoortempf' => ['numeric'],
            // Indoor Humidity (0-100 %)
            'indoorhumidity' => ['numeric'],
        ];
    }
}
