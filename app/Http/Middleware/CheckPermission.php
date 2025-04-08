<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $page)
    {
        $user = Auth::user();
        if (!$user) {
            Log::info('User not authenticated, redirecting to login');
            return redirect()->route('login');
        }

        Log::info('Checking permission for user: ' . $user->id . ', page: ' . $page);

        if ($user->isSuperAdmin()) {
            Log::info('User is super admin, allowing access');
            return $next($request);
        }

        $role = $user->role;
        $hasPermission = $role && $role->permissions->where('page_name', $page)->first()?->pivot->can_view;

        if (!$hasPermission) {
            Log::info('Permission denied for page: ' . $page . ', redirecting to no-access');
            return redirect()->route('no-access');
        }

        Log::info('Permission granted for page: ' . $page);
        return $next($request);
    }
}