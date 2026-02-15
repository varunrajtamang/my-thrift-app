<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Filament\Notifications\Notification;

class FilamentAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return $next($request);
        }

        // If user is logged in but not an admin
        if (Auth::check()) {
            Auth::logout();

            // Flash a message to the session
            session()->flash('error', 'Only administrators can access this area.');

            return redirect()->route('filament.admin.auth.login');
        }

        // If not logged in at all, just continue with the request
        // (which will likely redirect to login page anyway)
        return $next($request);
    }
}
