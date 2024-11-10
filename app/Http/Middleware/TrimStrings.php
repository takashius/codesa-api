<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * Los nombres de los atributos que no deben ser recortados.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
