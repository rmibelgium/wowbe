<?php

namespace App\Http\Requests\API;

use App\Models\Site;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SendRequest extends FormRequest implements SendRequestInterface
{
    /**
     * Extract the Site ID from the request.
     */
    public function extractSiteID(): ?string
    {
        return $this->input('siteid');
    }

    /**
     * Extract the Authentication Key from the request.
     */
    public function extractAuthKey(): ?string
    {
        return $this->input('siteAuthenticationKey');
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $siteID = $this->extractSiteID();
        $siteAuthenticationKey = $this->extractAuthKey();

        if (Str::isUuid($siteID) === true) {
            /** @var ?Site $site */
            $site = Site::find($siteID);
        } else {
            /** @var ?Site $site */
            $site = Site::where('short_id', $siteID)->first();
        }

        return ! is_null($site) && ! is_null($siteAuthenticationKey) && $site->auth_key === $siteAuthenticationKey;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $dateutc = urldecode($this->input('dateutc'));
        $dateutc = strtotime($dateutc);
        if ($dateutc !== false) {
            $dateutc = date('Y-m-d H:i:s', $dateutc);
        }

        $this->merge([
            'dateutc' => $dateutc,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Site ID
            'siteid' => ['required', 'string', new \App\Rules\SiteID],
            // Authentication Key (PIN code or Password)
            'siteAuthenticationKey' => ['required', 'string'],
            // Date & Time in UTC
            'dateutc' => ['required', 'date_format:Y-m-d H:i:s'],
            // Software Type
            'softwaretype' => ['required', 'string'],
            // Relative Barometric Pressure (at site location) (Inch of Mercury)
            'baromin' => ['numeric'],
            // Absolute Barometric Pressure - Mean Sea Level Pressure (MSLP) (Inch of Mercury)
            'absbaromin' => ['numeric'],
            // Daily Accumulated rainfall so far today (Inches)
            'dailyrainin' => ['numeric'],
            // Outdoor Dewpoint (Fahrenheit)
            'dewptf' => ['numeric'],
            // Outdoor Humidity (0-100 %)
            'humidity' => ['numeric'],
            // Accumulated rainfall since the previous observation (Inches)
            'rainin' => ['numeric'],
            // % Moisture (0-100 %)
            'soilmoisture' => ['numeric'],
            // Soil Temperature (10cm) (Fahrenheit)
            'soiltempf' => ['numeric'],
            // Outdoor Temperature (Fahrenheit)
            'tempf' => ['numeric'],
            // Visibility (Kilometres)
            'visibility' => ['numeric'],
            // Instantaneous Wind Direction (Degrees (0-360))
            'winddir' => ['numeric'],
            // Instantaneous Wind Speed (Miles per Hour)
            'windspeedmph' => ['numeric'],
            // Current Wind Gust Direction (using software specific time period) (Degrees (0-360))
            'windgustdir' => ['numeric'],
            // Current Wind Gust Speed (using software specific time period) (Miles per Hour)
            'windgustmph' => ['numeric'],
            // Solar Radiation (Watt per Square Metre)
            'solarradiation' => ['numeric'],
        ];
    }
}
