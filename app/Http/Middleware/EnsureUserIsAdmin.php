<?php

namespace App\Http\Middleware;

use Closure;

class EnsureUserIsAdmin
{
    /**
     * Reject non-admin users. Must run after the 'auth' middleware
     * so that $request->auth is already populated.
     */
    public function handle($request, Closure $next)
    {
        if (!$request->auth || !$request->auth->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Khusus admin.'
            ], 403);
        }

        return $next($request);
    }
}
