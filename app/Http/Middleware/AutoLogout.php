<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AutoLogout
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity', time());
            $timeout = 15 * 60; // 15 menit dalam detik

            if (time() - $lastActivity > $timeout) {
                Auth::logout();
                session()->flush();
                session()->put('auto_logout', true); // Simpan session untuk SweetAlert
                return redirect()->route('login');
            }

            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}

