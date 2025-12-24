<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Notifications\SendVerificationCode;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected Client $client;

    public function __construct()
    {
        $this->client = request()->user("api");
    }

    public function resendVerification(Request $request)
    {
        $type = $request->route("type");

        if(!$this->client->{$type})
        {
            return errorJsonResponse(__('auth.verification.missing'));
        }

        $metaVerification = $this->client->generateVerificationMeta($type);
        $code = data_get($metaVerification, "{$type}.code");

        $this->client->notify(new SendVerificationCode($this->client, $code, $type));
        
        return successJsonResponse(__('auth.verification.verification_code_resent'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            "code" => "required|string",
        ]);

        $type = $request->route("type");

        $code = $this->client->getCodeVerification($type);


        if ($request->input("code") != $code || $this->client->isExpiredVerification($type)) {
            return errorJsonResponse(__('auth.verification.invalid_code'));
        }

        $this->client->markAsVerified($type);

        return successJsonResponse(__('auth.verification.code_verified'));
    }
}
