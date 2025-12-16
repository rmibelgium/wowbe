<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class WeatherUndergroundSendRequest extends FormRequest
{
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
            // Site ID
            'ID' => ['required', 'string', new \App\Rules\SiteID],
            // Authentication Key (PIN code or Password)
            'PASSWORD' => ['required', 'string'],
            // Date & Time in UTC
            'dateutc' => ['required', 'date_format:Y-m-d H:i:s'],

            // Action (supposed to be `action=updateraw`)
            'action' => ['string'],
            // Relative Pressure i.e. pressure adjusted to sea level (Inch of Mercury)
            'baromin' => ['numeric'],
            // Daily Accumulated rainfall so far today (Inches)
            'dailyrainin' => ['numeric'],
            // Outdoor Dewpoint (Fahrenheit)
            'dewptf' => ['numeric'],
            // Outdoor Humidity (0-100 %)
            'humidity' => ['numeric'],
            // Instantaneous Rain Rate (Inches/h)
            'rainin' => ['numeric'],
            // % Moisture (0-100 %)
            'soilmoisture' => ['numeric'],
            // Soil Temperature (10cm) (Fahrenheit)
            'soiltempf' => ['numeric'],
            // Solar Radiation (Watt per Square Metre)
            'solarradiation' => ['numeric'],
            // Software Type
            'softwaretype' => ['string'],
            // Outdoor Temperature (Fahrenheit)
            'tempf' => ['numeric'],
            // UV Index
            'UV' => ['numeric'],
            // Visibility (Kilometres)
            'visibility' => ['numeric'],
            // Instantaneous Wind Direction (Degrees (0-360))
            'winddir' => ['numeric'],
            // Current Wind Gust Direction (using software specific time period) (Degrees (0-360))
            'windgustdir' => ['numeric'],
            // Current Wind Gust Speed (using software specific time period) (Miles per Hour)
            'windgustmph' => ['numeric'],
            // Instantaneous Wind Speed (Miles per Hour)
            'windspeedmph' => ['numeric'],

            /**
             * Absolute Pressure i.e. raw pressure measurement at the station's location (Inch of Mercury)
             * *Not part of WeatherUnderground protocol but supported by WOW-BE backend.*
             */
            'absbaromin' => ['numeric'],
            /**
             * Site Model
             * *Not part of WeatherUnderground protocol but supported by WOW-BE backend.*
             */
            'model' => ['string'],

            // SKC, FEW, SCT, BKN, OVC
            // 'clouds' => ['string'],
            // Indoor Humidity (0-100 %)
            // 'indoorhumidity' => ['numeric'],
            // Indoor Temperature (Fahrenheit)
            // 'indoortempf' => ['numeric'],
            // Leaf Wetness (0-100 %)
            // 'leafwetness' => ['numeric'],
            // metar style (+RA)
            // 'weather' => ['string'],
            // 0-360 2 minute average wind direction
            // 'winddir_avg2m' => ['numeric'],
            // 0-360 past 10 minutes wind gust direction
            // 'windgustdir_10m' => ['numeric'],
            // mph past 10 minutes wind gust mph
            // 'windgustmph_10m' => ['numeric'],
            // mph 2 minute average wind speed mph
            // 'windspdmph_avg2m' => ['numeric'],
        ];
    }
}
