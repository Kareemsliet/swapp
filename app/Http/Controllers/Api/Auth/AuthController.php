<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $client = Client::create($data);
        
        $token = JWTAuth::fromUser($client);

        return successJsonResponse(
            __('auth.registered'),
            [
                "access_token" => $token,
            ],
        );
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $column = data_has($credentials, 'email') ? 'email' : 'phone';

        $columnValue = data_get($credentials, $column);

        $client = Client::where($column, '=', $columnValue)->first();
        
        if(!$client)
        {
            return errorJsonResponse(__('auth.failed'));
        }

        if(!Hash::check($credentials["password"],$client->password))
        {
            return errorJsonResponse(__('auth.failed'));
        }
        
        $token = JWTAuth::fromUser($client);

        return successJsonResponse(
            __('auth.login'),
            [
                "access_token" => $token,
            ],
        );
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken(), true);

        return successJsonResponse(__('auth.logout'));
    }

    public function check()
    {
        $client = auth("api")->user();

        $data = [
            "authenticated" => !is_null($client),
        ];

        if($client)
        {
            $data["is_verified"] = $client->hasVerified();
            
            $data["client"] = ClientResource::make($client);
        }

        return successJsonResponse(data:$data);
    }

}
