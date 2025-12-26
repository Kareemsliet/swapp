<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileUpdate;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

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

        if($request->input('email'))
        {
            $data = $this->changeEmail($data);
        }

        if($request->input('phone'))
        {
            $data = $this->changePhone($data);
        }

        $this->client->update($data);

        return successJsonResponse(
            __('general.response.success'),
        );
    }

    protected function changeEmail(array $data)
    {
        $newEmail = data_get($data, 'email');

        if($this->client->email == $newEmail)
        {
            return $data;
        }

        $metaVerification = $this->client->generateVerificationMeta('email');
        $code = data_get($metaVerification, 'email.code');

        $this->client->notify(new \App\Notifications\SendVerificationCode($this->client, $code, 'email'));

        return array_merge($data, [
            'email_verified_at' => null,
            'email' => $newEmail,
        ]);
    }

    protected function changePhone(array $data)
    {
        $phone = data_get($data, 'phone');

        if ($this->client->phone === $phone) {
            return $data;
        }

        $metaVerification = $this->client->generateVerificationMeta('phone');
        $code = data_get($metaVerification, 'phone.code');

        $this->client->notify(new \App\Notifications\SendVerificationCode($this->client, $code, 'phone'));

        return array_merge($data, [
            'phone_verified_at' => null,
            'phone' => $phone,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            "password" => "required|string|confirmed|min:8|different:old_password",
        ]);

        $this->client->password = $request->input("password");
        $this->client->save();

        return successJsonResponse(__('general.response.success'));
    }
}