<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'person_id',
        'doc_tipo',
        'doc_pais',
        'doc_num',
        'nombre',
        'apellido',
        'sexo',
        'f_nacimiento',
        'nacionalidad',
        'direccion',
        'telefono',
        'institucion',
        'curso',
        'desde',
        'hasta',
        'categoria',
        'agencia',
        'agente',
        'f_constancia',
        'tipo_funcionario',
        'funcionario',
        'f_licencia_conducir',
        'cargo',
        'observaciones',
        'kms'
    ];
}
