<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class EcowittSendRequest extends FormRequest
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
            // PassKey (MD5 hash of site MAC-address)
            'PASSKEY' => ['required', 'string'],
            // Station Type
            'stationtype' => ['required', 'string'],

            // Absolute Barometric Pressure (Inch of Mercury)
            'baromabsin' => ['numeric'],
            // Relative Barometric Pressure (Inch of Mercury)
            'baromrelin' => ['numeric'],
            // Daily Accumulated rainfall so far today (Inches)
            'dailyrainin' => ['numeric'],
            // Outdoor Humidity (0-100 %)
            'humidity' => ['numeric'],
            // Site Model
            'model' => ['string'],
            // Rain Rate (Inches)
            'rainratein' => ['numeric'],
            // Solar Radiation (Watt per Square Metre)
            'solarradiation' => ['numeric'],
            // Outdoor Temperature (Fahrenheit)
            'tempf' => ['numeric'],
            // Instantaneous Wind Direction (Degrees (0-360))
            'winddir' => ['numeric'],
            // Current Wind Gust Speed (using software specific time period) (Miles per Hour)
            'windgustmph' => ['numeric'],
            // Instantaneous Wind Speed (Miles per Hour)
            'windspeedmph' => ['numeric'],

            /**
             * Following parameters are NOT part of Ecowitt protocol but are supported by WOW protocol.
             * Those parameters WILL be stored in WOW-BE.
             */

            // Absolute Barometric Pressure - Mean Sea Level Pressure (MSLP) (Inch of Mercury)
            // 'absbaromin' => ['numeric'], // Overwritten by `baromabsin`
            // Relative Barometric Pressure (at site location) (Inch of Mercury)
            // 'baromin' => ['numeric'], // Overwritten by `baromrelin`
            // Outdoor Dewpoint (Fahrenheit)
            'dewptf' => ['numeric'],
            // Accumulated rainfall since the previous observation (Inches)
            // 'rainin' => ['numeric'], // Overwritten by `rainratein`
            // Software Type
            // 'softwaretype' => ['string'], // Overwritten by `stationtype`
            // % Moisture (0-100 %)
            'soilmoisture' => ['numeric'],
            // Soil Temperature (10cm) (Fahrenheit)
            'soiltempf' => ['numeric'],
            // Visibility (Kilometres)
            'visibility' => ['numeric'],
            // Current Wind Gust Direction (using software specific time period) (Degrees (0-360))
            'windgustdir' => ['numeric'],

            /**
             * Following parameters are part of Ecowitt protocol but are NOT supported by WOW protocol.
             * Those parameters WON'T be stored in WOW-BE.
             */

            // Event Rain (Inches)
            'eventrainin' => ['numeric'],
            // Frequency
            'frequency' => ['string'],
            // Hourly Rain (Inches)
            'hourlyrainin' => ['numeric'],
            // Indoor Humidity (0-100 %)
            'humidityin' => ['numeric'],
            // Maximum Daily Gust Speed (Miles per Hour)
            'maxdailygust' => ['numeric'],
            // Monthly Rain (Inches)
            'monthlyrainin' => ['numeric'],
            // Indoor Temperature (Fahrenheit)
            'tempinf' => ['numeric'],
            // Total Rain (Inches)
            'totalrainin' => ['numeric'],
            // UV Index
            'uv' => ['numeric'],
            // VPD
            'vpd' => ['numeric'],
            // Weekly Rain (Inches)
            'weeklyrainin' => ['numeric'],
            // WH65Batt
            'wh65batt' => ['numeric'],
            // Yearly Rain (Inches)
            'yearlyrainin' => ['numeric'],

        ];
    }
}
