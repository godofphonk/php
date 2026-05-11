<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsInstructor
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check() || ! Auth::user()->isInstructor()) {
            abort(403, 'Доступ запрещен');
        }

        return $next($request);
    }
}
