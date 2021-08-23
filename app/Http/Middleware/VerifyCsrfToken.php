<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "hook-enrollment-progress",
        "hook-enrollment-complete",
        "save-customer",
        "login-to-igo4less",
        'bitpay/callback',
        'bitpay/refund',
        'sor-reservations',
        'skrill/callback',
        'skrill/cancel',
        'rank-check',
        'esigngenie-callback'
    ];
}
