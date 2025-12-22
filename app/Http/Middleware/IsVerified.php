<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string|null $type = null): Response
    {
        $client = $request->user("api");

        $verified = is_null($type) ? $client->hasVerified() : $client->hasVerifiedBy($type);

        if (!$verified) {
            return forbiddenJsonResponse(__('auth.verified'), 403);
        }

        return $next($request);
    }
}
