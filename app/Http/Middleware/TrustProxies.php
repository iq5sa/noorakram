<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Request as RequestAlias;

class TrustProxies extends \Illuminate\Http\Middleware\TrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = RequestAlias::HEADER_X_FORWARDED_FOR | RequestAlias::HEADER_X_FORWARDED_HOST | RequestAlias::HEADER_X_FORWARDED_PORT | RequestAlias::HEADER_X_FORWARDED_PROTO;
}
