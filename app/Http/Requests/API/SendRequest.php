<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SendRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Date & Time in UTC
            'dateutc' => ['required', 'date_format:Y-m-d H:i:s'],
            // Authentication Key (PIN code or Password)
            'siteAuthenticationKey' => ['required', 'string'],
            // Site ID
            'siteid' => ['required', 'string', new \App\Rules\SiteID],

            // Software Type
            'softwaretype' => ['string'],
            // Absolute Barometric Pressure - Mean Sea Level Pressure (MSLP) (Inch of Mercury)
            'absbaromin' => ['numeric'],
            // Relative Barometric Pressure (at site location) (Inch of Mercury)
            'baromin' => ['numeric'],
            // Daily Accumulated rainfall so far today (Inches)
            'dailyrainin' => ['numeric'],
            // Outdoor Dewpoint (Fahrenheit)
            'dewptf' => ['numeric'],
            // Outdoor Humidity (0-100 %)
            'humidity' => ['numeric'],
            // Site Model
            'model' => ['string'],
            // Accumulated rainfall since the previous observation (Inches)
            'rainin' => ['numeric'],
            // % Moisture (0-100 %)
            'soilmoisture' => ['numeric'],
            // Soil Temperature (10cm) (Fahrenheit)
            'soiltempf' => ['numeric'],
            // Solar Radiation (Watt per Square Metre)
            'solarradiation' => ['numeric'],
            // Outdoor Temperature (Fahrenheit)
            'tempf' => ['numeric'],
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
        ];
    }
}
