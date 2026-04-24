<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHospital
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('hospital')->check()) {
            return redirect()->route('hospital.login');
        }

        if (!auth('hospital')->user()->isApproved()) {
            auth('hospital')->logout();
            return redirect()->route('hospital.login')
                ->withErrors(['email' => 'Your account is pending admin approval.']);
        }

        return $next($request);
    }
}
