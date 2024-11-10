<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indica si las cookies de la respuesta deben estar habilitadas.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * Las URIs que deberían estar excluidas de la verificación CSRF.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
