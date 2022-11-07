<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleOrPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roleOrPermission)
    {
        if (! backpack_auth()->user()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (! backpack_auth()->user()->hasAnyRole($rolesOrPermissions) && ! backpack_auth()->user()->hasAnyPermission($rolesOrPermissions)) {
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
        }

        return $next($request);
    }
}
