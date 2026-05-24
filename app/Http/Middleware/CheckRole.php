<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $expected = UserRole::tryFrom($role);
        if (! $expected || auth()->user()->role !== $expected) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
