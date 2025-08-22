<?php

namespace App\Http\Requests\API;

interface SendRequestInterface
{
    /**
     * Extract the Site ID from the request.
     */
    public function extractSiteID(): ?string;

    /**
     * Extract the Authentication Key from the request.
     */
    public function extractAuthKey(): ?string;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array;
}
