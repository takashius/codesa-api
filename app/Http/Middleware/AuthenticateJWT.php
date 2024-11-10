<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class AuthenticateJWT
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
            Auth::setUser($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        return $next($request);
    }
}
