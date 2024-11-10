<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        return response()->json(['message' => 'Usuario no encontrado'], 404);
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            if ($user->email_verified_at == null) {
                return response()->json(['message' => 'Su correo electrónico no ha sido verificado.'], 403);
            }

            if (Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Login exitoso'], 200);
            } else {
                return response()->json(['message' => 'Credenciales incorrectas'], 401);
            }
        } else {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    }
}
