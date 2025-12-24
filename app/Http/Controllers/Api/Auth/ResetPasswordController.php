<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Propaganistas\LaravelPhone\PhoneNumber;

class ResetPasswordController extends Controller
{
    public function forgetPasswordByEmail(Request $request)
    {
        $request->validate([
            "email" => "required|string|email:filter",
        ]);

        $client = Client::where('email', $request->input('email'))->first();

        if (!$client) {
            return errorJsonResponse(__('auth.password.missing'));
        }

        $metaReset = $client->generatePasswordResetMeta();
        $code = data_get($metaReset, 'code', null);

        $client->notify(new \App\Notifications\SendResetPasswordCode($client, $code, 'email'));

        return successJsonResponse(__('auth.password.code_sent'));
    }

    public function forgetPasswordByPhone(Request $request)
    {
        $request->validate([
            "country_code" => "required|string|size:2",
            "phone" => "required|string|phone:country_code",
        ]);

        $phone = (string) new PhoneNumber(
            $request->input('phone'),
            strtoupper($request->input('country_code'))
        );

        $client = Client::where('phone', $phone)->first();

        if (!$client) {
            return errorJsonResponse(__('auth.password.missing'));
        }

        $metaReset = $client->generatePasswordResetMeta();
        $code = data_get($metaReset, 'code',null);

        $client->notify(new \App\Notifications\SendResetPasswordCode($client, $code, 'phone'));

        return successJsonResponse(__('auth.password.code_sent'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            "code" => "required|string",
            "password" => "required|string|min:8|confirmed",
        ]);

        $client = Client::whereNotNull('password_reset_meta')->get()->first(function (Client $client) use ($request) {
            return $client->getCodePasswordReset() == $request->input('code');
        });

        if (!$client || $client->isExpiredPasswordReset()) {
            return errorJsonResponse(__('auth.password.invalid_code'));
        }

        $client->password = $request->input('password');
        $client->password_reset_meta = null;
        $client->save();

        return successJsonResponse(__('auth.password.reset_successful'));
    }
}
