<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailVerification;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ], 201);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $user = User::where('email', $credentials['email'])->first();
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
            if (!$user->hasVerifiedEmail()) {
                return response()->json(['error' => 'Su correo electrónico no ha sido verificado.'], 403);
            }

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not refresh token'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not log out'], 500);
        }
    }

    public function sendVerificationEmail(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->hasVerifiedEmail()) {
            // return response()->json(['message' => 'Email already verified'], 200);
        }

        $verificationToken = Str::random(60);

        EmailVerification::create([
            'user_id' => $user->id,
            'token' => $verificationToken
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user, $verificationToken));
        return response()->json(['message' => 'Verification email sent successfully'], 200);
    }

    public function verifyEmail($token)
    {
        $verification = EmailVerification::where('token', $token)->first();

        if (!$verification) {
            return response()->json(['error' => 'Invalid verification token'], 404);
        }

        $user = $verification->user;

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email verified successfully'], 200);
    }
}