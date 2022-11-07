<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $perm)
    {
        if (! backpack_auth()->user()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($perm)
            ? $perm
            : explode('|', $perm);

        foreach ($permissions as $permission) {
            if (backpack_auth()->user()->can($permission)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
