<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Use the 'admin' guard
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();

            if ($admin->role === 'super_admin') {
                return $next($request);
            } else {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error', 'Access denied.');
            }
        }

        return redirect()->route('admin.login')->with('error', 'Please log in.');
    }
}
