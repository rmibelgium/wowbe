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
            'stationtype' => ['string'],
            // Absolute Pressure i.e. raw pressure measurement at the station's location (Inch of Mercury)
            'baromabsin' => ['numeric'],
            // Relative Pressure i.e. pressure adjusted to sea level (Inch of Mercury)
            'baromrelin' => ['numeric'],
            // Daily Accumulated rainfall so far today (Inches)
            'dailyrainin' => ['numeric'],
            // Outdoor Humidity (0-100 %)
            'humidity' => ['numeric'],
            // Site Model
            'model' => ['string'],
            // Instantaneous Rain Rate (Inches/h)
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

            // 'absbaromin' => ['numeric'], // Overwritten by `baromabsin`
            // 'baromin' => ['numeric'], // Overwritten by `baromrelin`
            // 'rainin' => ['numeric'], // Overwritten by `rainratein`
            // 'softwaretype' => ['string'], // Overwritten by `stationtype`

            /**
             * Outdoor Dewpoint (Fahrenheit)
             * *Not part of Ecowitt protocol but supported by WOW-BE backend.*
             */
            'dewptf' => ['numeric'],
            /**
             * % Moisture (0-100 %)
             * *Not part of Ecowitt protocol but supported by WOW-BE backend.*
             */
            'soilmoisture' => ['numeric'],
            /**
             * Soil Temperature (10cm) (Fahrenheit)
             * *Not part of Ecowitt protocol but supported by WOW-BE backend.*
             */
            'soiltempf' => ['numeric'],
            /**
             * Visibility (Kilometres)
             * *Not part of Ecowitt protocol but supported by WOW-BE backend.*
             */
            'visibility' => ['numeric'],
            /**
             * Current Wind Gust Direction (using software specific time period) (Degrees (0-360))
             * *Not part of Ecowitt protocol but supported by WOW-BE backend.*
             */
            'windgustdir' => ['numeric'],

            // Event Rain (Inches)
            // 'eventrainin' => ['numeric'],
            // Frequency
            // 'frequency' => ['string'],
            // Hourly Rain (Inches)
            // 'hourlyrainin' => ['numeric'],
            // Indoor Humidity (0-100 %)
            // 'humidityin' => ['numeric'],
            // Maximum Daily Gust Speed (Miles per Hour)
            // 'maxdailygust' => ['numeric'],
            // Monthly Rain (Inches)
            // 'monthlyrainin' => ['numeric'],
            // Indoor Temperature (Fahrenheit)
            // 'tempinf' => ['numeric'],
            // Total Rain (Inches)
            // 'totalrainin' => ['numeric'],
            // UV Index
            // 'uv' => ['numeric'],
            // VPD
            // 'vpd' => ['numeric'],
            // Weekly Rain (Inches)
            // 'weeklyrainin' => ['numeric'],
            // WH65Batt
            // 'wh65batt' => ['numeric'],
            // Yearly Rain (Inches)
            // 'yearlyrainin' => ['numeric'],

        ];
    }
}
