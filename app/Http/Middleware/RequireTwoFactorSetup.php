<?php

namespace App\Http\Middleware;

use App\TwoFactorSetting;
use Closure;
use Illuminate\Http\Request;

/**
 * Blocks users whose role requires 2FA, who have not enrolled, and whose grace
 * period has run out. They can only reach the 2FA setup routes until they enrol.
 */
class RequireTwoFactorSetup
{
   public function handle(Request $request, Closure $next)
   {
      if (!auth()->check()) {
         return $next($request);
      }

      $user = auth()->user();

      if (!$user->requiresTwoFactor() || $user->hasTwoFactorEnabled()) {
         return $next($request);
      }

      // Never trap the user on the pages they need to escape.
      if ($request->routeIs('2fa.setup', '2fa.confirm', '2fa.manage', 'logout')) {
         return $next($request);
      }

      $twoFactor = $user->twoFactorAuth;

      if (!$twoFactor || is_null($twoFactor->required_since)) {
         return $next($request);
      }

      $deadline = $twoFactor->required_since->copy()
         ->addDays(TwoFactorSetting::current()->grace_period_days);

      if (now()->greaterThan($deadline)) {
         return redirect()->route('2fa.setup')
            ->with('warning', 'Tempoh tangguh pengesahan dua faktor telah tamat. Sila sediakan sekarang.');
      }

      return $next($request);
   }
}
