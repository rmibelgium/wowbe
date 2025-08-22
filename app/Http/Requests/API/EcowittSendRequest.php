<?php

namespace App\Http\Requests\API;

use App\Models\Site;
use Illuminate\Foundation\Http\FormRequest;

class EcowittSendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $passkey = $this->input('passkey');

        return Site::where('mac_address', $passkey)->exists();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $dateutc = urldecode($this->dateutc);
        $dateutc = strtotime($dateutc);
        if ($dateutc !== false) {
            $dateutc = date('Y-m-d H:i:s', $dateutc);
        }

        $this->merge(['dateutc' => $dateutc]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // PassKey (MD5 hash of site MAC-address)
            'passkey' => ['required', 'string'],
            // Date & Time in UTC
            'dateutc' => ['required', 'date_format:Y-m-d H:i:s'],
            // Station Type
            'stationtype' => ['required', 'string'],
            // Relative Barometric Pressure (Inch of Mercury)
            'baromrelin' => ['numeric'],
            // Absolute Barometric Pressure (Inch of Mercury)
            'baromabsin' => ['numeric'],
            // Outdoor Temperature (Fahrenheit)
            'tempf' => ['numeric'],
            // Outdoor Humidity (0-100 %)
            'humidity' => ['numeric'],
            // Instantaneous Wind Direction (Degrees (0-360))
            'winddir' => ['numeric'],
            // Instantaneous Wind Speed (Miles per Hour)
            'windspeedmph' => ['numeric'],
            // Current Wind Gust Speed (using software specific time period) (Miles per Hour)
            'windgustmph' => ['numeric'],
            // Rain Rate (Inches)
            'rainratein' => ['numeric'],
            // Daily Accumulated rainfall so far today (Inches)
            'dailyrainin' => ['numeric'],
            // Solar Radiation (Watt per Square Metre)
            'solarradiation' => ['numeric'],
            // Site Model
            'model' => ['string'],

            /**
             * Following parameters are NOT part of Ecowitt protocol but are supported by WOW protocol.
             * Those parameters WILL be stored in WOW-BE.
             */

            // Software Type
            // 'softwaretype' => ['string'], // Overwritten by `stationtype`
            // Relative Barometric Pressure (at site location) (Inch of Mercury)
            // 'baromin' => ['numeric'], // Overwritten by `baromrelin`
            // Absolute Barometric Pressure - Mean Sea Level Pressure (MSLP) (Inch of Mercury)
            // 'absbaromin' => ['numeric'], // Overwritten by `baromabsin`
            // Outdoor Dewpoint (Fahrenheit)
            'dewptf' => ['numeric'],
            // Accumulated rainfall since the previous observation (Inches)
            // 'rainin' => ['numeric'], // Overwritten by `rainratein`
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

            // Indoor Temperature (Fahrenheit)
            'tempinf' => ['numeric'],
            // Indoor Humidity (0-100 %)
            'humidityin' => ['numeric'],
            // Maximum Daily Gust Speed (Miles per Hour)
            'maxdailygust' => ['numeric'],
            // Event Rain (Inches)
            'eventrainin' => ['numeric'],
            // Hourly Rain (Inches)
            'hourlyrainin' => ['numeric'],
            // Weekly Rain (Inches)
            'weeklyrainin' => ['numeric'],
            // Monthly Rain (Inches)
            'monthlyrainin' => ['numeric'],
            // Yearly Rain (Inches)
            'yearlyrainin' => ['numeric'],
            // Total Rain (Inches)
            'totalrainin' => ['numeric'],
            // UV Index
            'uv' => ['numeric'],
            // VPD
            'vpd' => ['numeric'],
            // WH65Batt
            'wh65batt' => ['numeric'],
            // Frequency
            'frequency' => ['string'],

        ];
    }
}
