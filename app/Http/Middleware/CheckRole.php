<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ) {
        $user = $request->user();

        // Check authentication
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin has full access
        if ($user->isAdmin()) {
            return $next($request);
        }

        foreach ($roles as $role) {

            // Committee access:
            // Student with active committee membership
            if ($role === 'committee') {

                if ($user->isCommittee()) {
                    return $next($request);
                }

                continue;
            }

            // Normal role verification
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'You are not authorized to access this page.');
    }
}
