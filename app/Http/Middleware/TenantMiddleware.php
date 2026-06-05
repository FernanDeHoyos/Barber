<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $host = $request->getHost();

        $subdomain = null;

        if ($host !== 'localhost' && str_ends_with($host, '.localhost')) {
            $parts = explode('.', $host);
            $subdomain = $parts[0];
        }

        if (! $subdomain) {
            return $next($request);
        }

        $tenant = Tenant::where('slug', $subdomain)
            ->first();

        if (! $tenant) {
            abort(404, 'Barbería no encontrada');
        }

        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
