<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileUpdate;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    protected Client $client;

    public function __construct()
    {
        $this->client = request()->user("api");
    }

    public function show(Request $request)
    {
        return successJsonResponse(data: ClientResource::make($this->client));
    }

    public function update(ProfileUpdate $request)
    {
        $data = $request->validated();

        $this->client->update($data);

        return successJsonResponse(
            __('general.response.success'),
        );
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            "email" => "required|string|email:filter|unique:clients,email,{$this->client->id}",
        ]);

        $newEmail = $request->input("email");

        if ($this->client->email === $newEmail) {
            return successJsonResponse(__('general.response.no_changes'));
        }

        $this->client->email = $newEmail;
        $this->client->email_verified_at = null;
        $this->client->save();

        $metaVerification = $this->client->generateVerificationMeta('email');
        $code = data_get($metaVerification, 'email.code');

        $this->client->notify(new \App\Notifications\SendVerificationCode($this->client, 'email', $code));

        return successJsonResponse(__('auth.verification.code_sent'));
    }

    public function changePhone(Request $request)
    {
        $request->validate([
            "phone" => "required|string|phone:country_code|unique:clients,phone,{$this->client->id}",
            "country_code" => "required|string|size:2",
        ]);

        $phone = (string) new \Propaganistas\LaravelPhone\PhoneNumber(
            $request->input("phone"),
            strtoupper($request->input("country_code"))
        );

        if ($this->client->phone === $phone) {
            return successJsonResponse(__('general.response.success'));
        }

        $this->client->phone = $phone;
        $this->client->phone_verified_at = null;
        $this->client->save();

        $metaVerification = $this->client->generateVerificationMeta('phone');
        $code = data_get($metaVerification, 'phone.code');

        $this->client->notify(new \App\Notifications\SendVerificationCode($this->client, 'phone', $code));

        return successJsonResponse(__('auth.verification.code_sent'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            "old_password" => "required|string|current_password:api",
            "password" => "required|string|confirmed|min:8|different:old_password",
        ]);

        $this->client->password = $request->input("password");
        $this->client->save();

        return successJsonResponse(__('general.response.success'));
    }

}