<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect('/login');
        }

        // Admin (role_id = 1) has access to everything
        if ($user->role_id == 1) {
            return $next($request);
        }

        $role = DB::table('role')->where('role_id', $user->role_id)->first();
        $permissions = json_decode($role->permissions ?? '[]', true) ?: [];

        if (!in_array($permission, $permissions)) {
            abort(404);
        }

        return $next($request);
    }
}