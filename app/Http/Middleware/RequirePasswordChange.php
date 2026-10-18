<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Keeps a user on the "Tetapkan Kata Laluan" page until they set their own password:
 * when an admin set it for them, or when it is older than User::PASSWORD_VALID_MONTHS.
 */
class RequirePasswordChange
{
   public function handle(Request $request, Closure $next)
   {
      if (!auth()->check()) {
         return $next($request);
      }

      $user = auth()->user();

      // An admin using "Login Sebagai" must not be made to change someone else's password.
      if (!$user->mustChangePassword() || session()->has('original_user_id')) {
         return $next($request);
      }

      if ($request->is('profile/force_password_change') || $request->routeIs('logout')) {
         return $next($request);
      }

      // TEMPORARY (FAT): Admin is not forced to change password. Remove this check after FAT.
      if ($user->hasRole('Admin')) {
         return $next($request);
      }

      $message = is_null($user->password_changed_at)
         ? 'Sila tetapkan kata laluan anda.'
         : 'Kata laluan anda telah tamat tempoh (6 bulan). Sila tukar kata laluan anda.';

      if ($request->expectsJson()) {
         return response()->json(['message' => $message], 403);
      }

      return redirect()->route('profile.force-password-change')->with('warning', $message);
   }
}
