<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialAuthController extends Controller
{
    public function handleProviderCallback(Request $request, string $provider)
    {
        $validated = $request->validate([
            'access_token' => 'required|string',
        ]);

        $providerUser = $this->verifyProviderToken(strtolower($provider), $validated['access_token']);

        if (!$providerUser) {
            return errorJsonResponse(__('auth.invalid_token'));
        }

        $client = $this->findOrCreateClient($provider, $providerUser);

        $token = JWTAuth::fromUser($client);

        return successJsonResponse(__('auth.login'), [
            'token' => $token,
        ]);
    }

    protected function verifyProviderToken(string $provider, string $accessToken): ?array
    {
        $response = match ($provider) {
            'google' => Http::get('https://www.googleapis.com/oauth2/v3/userinfo', [
                'access_token' => $accessToken
            ]),
            default => null,
        };

        return $response?->successful() ? $response->json() : null;
    }

    protected function findOrCreateClient(string $provider, array $providerUser): Client
    {
        $providerId = $providerUser['sub'] ?? $providerUser['id'];
        $email = $providerUser['email'] ?? null;
        $name = $providerUser['name'] ?? 'User';

        return DB::transaction(function () use ($provider, $providerId, $email, $name) {
            
            $client = Client::with('providers')
            ->whereHas('providers', fn($q) => $q->byProvider($provider)->byProviderId($providerId))
            ->first();

            if (!$client && $email) {
                $client = Client::where('email', $email)->with('providers')->first();
            }

            if (!$client) {
                $client = Client::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $providerId,
                    'email_verified_at' => now(),
                ]);
            } else {
                $client->providers()->firstOrCreate(
                    ['provider' => $provider, 'provider_id' => $providerId]
                );

                if ($client->name === 'User' && $name !== 'User') {
                    $client->update(['name' => $name]);
                }
            }

            if (!$client->relationLoaded('providers') || $client->providers->isEmpty()) {
                $client->providers()->create([
                    'provider' => $provider,
                    'provider_id' => $providerId,
                ]);
            }

            return $client;
        
        });
    }
}
