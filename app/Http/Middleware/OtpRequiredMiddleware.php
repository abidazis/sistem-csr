<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OtpRequiredMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !session('user_id_for_otp_verification')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}