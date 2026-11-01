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

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin has full access.
        if ($user->isAdmin()) {
            return $next($request);
        }

        foreach ($roles as $role) {

            // Committee membership is checked
            // through the Student relationship.
            if ($role === 'committee') {

                if ($user->isActiveCommitteeMember()) {
                    return $next($request);
                }

                continue;
            }

            // Normal role verification.
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'You are not authorized to access this page.');
    }
}
