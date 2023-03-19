<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'https://questanya.com/faceapp/*',
        'http://questanya.com/faceapp/*',
        'https://questanya.com/faceapp2/*',
        'http://questanya.com/faceapp2/*',
    ];
}
