<?php

namespace App\Http\Middleware;

use App\Role;
use Closure;
use Illuminate\Support\Facades\Log;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $adminRoleId = Role::where('name', 'admin')->first()->id;
        if ($request->user()->role_id === $adminRoleId) {
            return $next($request);
        }
        return response()->json('Unauthorized', 403);
    }
}
