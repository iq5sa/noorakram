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
        '/course-enrolment/*',
        '/courses/*',
        '/course-enrolment/*',
        '/admin/course-management/lesson/upload-video',
        '/upload-video',
        '/admin/*',
        '/test/*'
    ];
}
