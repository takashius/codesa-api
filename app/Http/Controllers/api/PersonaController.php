<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Persona;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\EmailVerification;

class PersonaController extends Controller
{
    public function getPersonByDocumentAndEmail(Request $request)
    {
        $docnum = $request->input('docnum');
        $email = $request->input('email');
        $password = $request->input('password');
        $credentials = $request->only('email', 'password');

        $persona = Persona::where('DOCNUM', $docnum)
            ->where('EMAIL', $email)
            ->first(['ID', 'DOCTIPO', 'DOCPAIS', 'DOCNUM', 'NOMBRE1', 'NOMBRE2', 'APELLIDO1', 'APELLIDO2', 'SEXO', 'FNACIMIENTO', 'NACIONALIDAD', 'DIRECCION', 'TELEFONO', 'EMAIL', 'INSTITUCION', 'CURSO', 'DESDE', 'HASTA', 'CATEGORIA', 'AGENCIA', 'AGENTE', 'FCONSTANCIA', 'TIPOFUNCIONARIO', 'FUNCIONARIO', 'FLICCONDUCIR', 'CARGO', 'OBS', 'KMS']);

        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        $userWithSameEmail = User::where('email', $email)->first();
        $userProfileWithSameDocnum = UserProfile::where('doc_num', $docnum)->first();

        if ($userWithSameEmail) {
            return response()->json(['message' => 'Ya existe un usuario con este correo electrónico'], 409);
        }

        if ($userProfileWithSameDocnum) {
            return response()->json(['message' => 'Ya existe un usuario con este número de documento'], 409);
        }

        $user = User::create([
            'name' => $persona->NOMBRE1 . ' ' . $persona->NOMBRE2 . ' ' . $persona->APELLIDO1 . ' ' . $persona->APELLIDO2,
            'email' => $persona->EMAIL,
            'password' => Hash::make($password),
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'person_id' => $persona->ID,
            'doc_tipo' => $persona->DOCTIPO,
            'doc_pais' => $persona->DOCPAIS,
            'doc_num' => $persona->DOCNUM,
            'nombre' => $persona->NOMBRE1 . ' ' . $persona->NOMBRE2,
            'apellido' => $persona->APELLIDO1 . ' ' . $persona->APELLIDO2,
            'sexo' => $persona->SEXO,
            'f_nacimiento' => $persona->FNACIMIENTO,
            'nacionalidad' => $persona->NACIONALIDAD,
            'direccion' => $persona->DIRECCION,
            'telefono' => $persona->TELEFONO,
            'institucion' => $persona->INSTITUCION,
            'curso' => $persona->CURSO,
            'desde' => $persona->DESDE,
            'hasta' => $persona->HASTA,
            'categoria' => $persona->CATEGORIA,
            'agencia' => $persona->AGENCIA,
            'agente' => $persona->AGENTE,
            'f_constancia' => $persona->FCONSTANCIA,
            'tipo_funcionario' => $persona->TIPOFUNCIONARIO,
            'funcionario' => $persona->FUNCIONARIO,
            'f_licencia_conducir' => $persona->FLICCONDUCIR,
            'cargo' => $persona->CARGO,
            'observaciones' => $persona->OBS,
            'kms' => $persona->KMS,
        ]);

        $verificationCode = rand(100000, 999999);

        EmailVerification::create([
            'user_id' => $user->id,
            'token' => $verificationCode,
        ]);

        $token = JWTAuth::attempt($credentials);
        $personaArray = $persona->toArray();
        $personaArray['token'] = $token;

        Mail::to($user->email)->send(new VerifyEmail($user, $verificationCode));

        return response()->json($personaArray);
    }
}
