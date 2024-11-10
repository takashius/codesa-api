<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Los proxies de confianza para esta aplicación.
     *
     * @var array|string|null
     */
    protected $proxies;

    /**
     * Las cabeceras que deben ser usadas para detectar proxies.
     *
     * @var int
     */
    protected $headers =
    Request::HEADER_FORWARDED |
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO;
}
