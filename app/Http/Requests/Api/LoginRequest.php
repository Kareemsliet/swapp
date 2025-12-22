<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class LoginRequest extends FormRequest
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
        return [
            "email" => [
                "required_without:phone",
                "nullable",
                "string",
                "email:filter",
                Rule::prohibitedIf($this->filled("phone")),
            ],
            "phone" => [
                "required_without:email",
                "phone:country_code",
                "nullable",
                "string",
                Rule::prohibitedIf($this->filled("email")),
            ],
            "country_code" => [
                "required_with:phone",
                "string",
                "size:2",
            ],
            "password" => "required|string|min:8",
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
