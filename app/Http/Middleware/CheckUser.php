<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && auth()->user()->hasRole('Nyahaktif')) // Sekiranya akaun peranan nyahaktif, akan disekat
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('home')->with('error', 'Akaun anda telah dinyahaktif. Hubungi agensi admin / Sistem Admin .');
        }

        if(
            auth()->check() && auth()->user()->hasRole('Agency Admin') || 
            auth()->check() && auth()->user()->hasRole('Agency User') ||
            auth()->check() && auth()->user()->hasRole('Agency Urusetia') ||
            auth()->check() && auth()->user()->hasRole('Agency Jawatankuasa') ||
            auth()->check() && auth()->user()->hasRole('Agency Lembaga Perolehan') ||
            auth()->check() && auth()->user()->hasRole('Lembaga Perolehan Negeri Selangor') ||
            auth()->check() && auth()->user()->hasRole('Admin UPEN') ||
            auth()->check() && auth()->user()->hasRole('Admin PWN') ||
            auth()->check() && auth()->user()->hasRole('Admin Kewangan')
          )
        {
            if(auth()->user()->arr != 1)  // Sekiranya akaun belum di semak, akan disekat
            {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('home')->with('error', 'Akaun anda tidak disemak. Semak pautan pada e-mail yang telah dihantar sebelum ini atau hubungi Agensi Admin .');
            }

            if(auth()->user()->confirmed != 1) // Sekiranya akaun tidak aktif, akan disekat
            {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('home')->with('error', 'Akaun anda tidak aktif. Hubungi agensi admin / Sistem Admin .');
            }
    
            // Auth::logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();

            // Password expiry (6 months, any way of changing it) is handled by RequirePasswordChange.

            // Auth::logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();
            // return redirect()->route('home')->with('error', 'Akaun anda tidak aktif. Hubungi agensi admin / Sistem Admin .');
        }

        return $next($request);
    }
    
}
