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
        
        // If role not found or no permissions set, deny access
        if (!$role) {
            abort(404);
        }
        
        $permissions = json_decode($role->permissions ?? '[]', true) ?: [];
        
        // If permissions is empty array, allow basic access for backward compatibility
        if (empty($permissions)) {
            // Allow dashboard access for all authenticated users
            if ($permission === 'dashboard') {
                return $next($request);
            }
            // For other permissions, check role-based defaults
            $roleDefaults = [
                2 => ['dashboard', 'evaluasi'], // Auditor
                3 => ['dashboard', 'penetapan', 'pelaksanaan'], // Unit Kerja
            ];
            
            if (isset($roleDefaults[$user->role_id]) && in_array($permission, $roleDefaults[$user->role_id])) {
                return $next($request);
            }
            
            abort(404);
        }

        if (!in_array($permission, $permissions)) {
            abort(404);
        }

        return $next($request);
    }
}