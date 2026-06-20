<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserBelongsToTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return $next($request);
        }

        $tenant = app()->bound('tenant')
            ? app('tenant')
            : null;

        if (! $tenant) {
            return $next($request);
        }

        $user = auth()->user();

        if ($user->role === 'superadmin') {
            return $next($request);
        }

        if ($user->tenant_id !== $tenant->id) {
            abort(403, 'No pertenece a esta barbería');
        }

        return $next($request);
    }
}
