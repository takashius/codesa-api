<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Los nombres de las cookies que no deben ser cifradas.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
