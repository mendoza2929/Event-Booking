<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\UserAccount;

class AuthenticateWithApiToken
{
   public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $token = str_replace('Bearer ', '', $token);
        $user = \App\UserAccount::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        Auth::setUser($user);
        return $next($request);
    }
}