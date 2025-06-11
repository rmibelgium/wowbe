<?php

namespace App\Http\Requests\API;

use App\Models\Site;
use Illuminate\Foundation\Http\FormRequest;

class SendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $siteid = $this->input('siteid');
        $siteAuthenticationKey = $this->input('siteAuthenticationKey');

        /** @var ?Site $site */
        $site = Site::find($siteid);

        return ! is_null($site) && $site->auth_key === $siteAuthenticationKey;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'siteid' => ['required', 'uuid'], // Site ID
            'siteAuthenticationKey' => ['required', 'string'], // Authentication Key
            'dateutc' => ['required', 'date', 'date_format:Y-m-d H:i:s'], // Date & Time in UTC
            'softwaretype' => ['required', 'string'], // Software Type

            'baromin' => ['numeric'], // Barometric Pressure (Inch of Mercury)
            'dailyrainin' => ['numeric'], // Daily Accumulated rainfall so far today (Inches)
            'dewptf' => ['numeric'], // Outdoor Dewpoint (Fahrenheit)
            'humidity' => ['numeric'], // Outdoor Humidity (0-100 %)
            'rainin' => ['numeric'], // Accumulated rainfall since the previous observation (Inches)
            'soilmoisture' => ['numeric'], // % Moisture (0-100 %)
            'soiltempf' => ['numeric'], // Soil Temperature (10cm) (Fahrenheit)
            'tempf' => ['numeric'], // Outdoor Temperature (Fahrenheit)
            'visibility' => ['numeric'], // Visibility (Kilometres)
            'winddir' => ['numeric'], // Instantaneous Wind Direction (Degrees (0-360))
            'windspeedmph' => ['numeric'], // Instantaneous Wind Speed (Miles per Hour)
            'windgustdir' => ['numeric'], // Current Wind Gust Direction (using software specific time period) (Degrees (0-360))
            'windgustmph' => ['numeric'], // Current Wind Gust Speed (using software specific time period) (Miles per Hour)

        ];
    }
}
