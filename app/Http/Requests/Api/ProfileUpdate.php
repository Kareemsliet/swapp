<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\PhoneNumber;

class ProfileUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $client = $this->user("api");

        return [
            "name" => "required|string|max:150|min:3",
            "birthdate" => "sometimes|date|date_format:Y-m-d|before:today",
            "summary" => "sometimes|string|max:450",
             "email" => [
                "required",
                "string",
                "email:filter",
                "unique:clients,email,{$client->id}",
            ],
            "phone" => [
                "nullable",
                "phone:country_code",
                "string",
                "unique:clients,phone,{$client->id}",
            ],
            "country_code" => [
                "required_with:phone",
                "string",
                "size:2",
            ],
        ];
    }

    public function prepareForValidation()
    {
        // Normalize country code to uppercase
        if ($this->has("country_code")) {
            $this->merge([
                "country_code" => strtoupper($this->input("country_code")),
            ]);
        }

        // Format phone number with country code
        if ($this->has("phone") && $this->has("country_code")) {
            $this->merge([
                "phone" => (string) new PhoneNumber($this->input("phone"), $this->input("country_code")),
            ]);
        }
    }

}
