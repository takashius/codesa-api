<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Recarga;
use App\Models\UserProfile;

class CardController extends Controller
{
    public function getData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $formattedStartDate = $startDate ? $startDate . ' 00:00:00' : null;
        $formattedEndDate = $endDate ? $endDate . ' 23:59:59' : null;
        $user = Auth::user();
        $userProfile = UserProfile::where('user_id', $user->id)->first();

        $query = DB::connection('codesa')->table('tarjetasrec as t')
            ->leftJoin('personas as p', 't.IDPERSONA', '=', 'p.ID')
            ->leftJoin('empresas2 as e', 't.IDEMPRESA', '=', 'e.ID')
            ->leftJoin('efactura as z', 't.EFACTURA', '=', 'z.ID')
            ->leftJoin('tarjetasbono as b', 'b.IDRECARGA', '=', 't.NUMMOV')
            ->select(
                't.NUMMOV',
                't.ESTADO',
                't.IDPERSONA',
                'p.DOCNUM',
                'p.NOMBRE1',
                'p.APELLIDO1',
                'p.FNACIMIENTO',
                't.IDTARJETA',
                't.CATEGORIA',
                't.TRAMO',
                't.VIAJE',
                't.INSTITUCION',
                'p.CURSO',
                't.FHRECARGA',
                't.CODBONO',
                't.RECARGA',
                't.IMPORTE',
                't.COBRADO',
                'e.NOMBRE',
                't.USUARIO',
                't.AGENCIA',
                'z.TIPO',
                'z.SERIE',
                'z.NUMERO',
                'b.IMPORTE',
                'b.COBRADO',
                't.MEDIOPAGO',
                'b.MODOPAGO',
                't.MEDIOPAGONUM'
            )
            ->where('t.ESTADO', '=', 3);

        if ($formattedStartDate) {
            $query->where('t.FHRECARGA', '>=', $formattedStartDate);
        }

        if ($formattedEndDate) {
            $query->where('t.FHRECARGA', '<=', $formattedEndDate);
        }

        $query->where('t.IDPERSONA', '=', $userProfile->person_id);

        $results = $query->get();

        return response()->json($results);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tarjeta_id' => 'required|exists:tarjetasbono,ID',
            'importe' => 'required|numeric|min:0',
        ]);

        $recarga = Recarga::create([
            'tarjeta_id' => $request->tarjeta_id,
            'user_id' => Auth::id(),
            'importe' => $request->importe,
            'fecha' => now(),
        ]);

        return response()->json([
            'message' => 'Recarga creada exitosamente',
            'data' => $recarga,
        ], 201);
    }
}
