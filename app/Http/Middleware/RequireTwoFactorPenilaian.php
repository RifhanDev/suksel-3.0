<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Services\TwoFactorPenilaianGate;
use App\Tender;
use App\TwoFactorPenilaianLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Asks for a 2FA code at the final Hantar of a penilaian stage, when the stage,
 * role and agensi rules on /two-factor say so. Applied at the route so the
 * controllers stay untouched and the popup cannot be skipped by calling the URL.
 *
 * Usage: ->middleware('2fa.penilaian:teknikal')
 * Kerja Kewangan borang that is the last step for one kerja type only: ->middleware('2fa.penilaian:kewangan,kerja_kecil_me')
 * The code arrives in the X-Two-Factor-Code header (sent by public/js/two-factor-penilaian.js).
 */
class RequireTwoFactorPenilaian
{
   use ResolvesTenderForProcess;

   public function __construct(private TwoFactorPenilaianGate $gate)
   {
   }

   public function handle(Request $request, Closure $next, string $peringkat, ?string $jenisKerja = null)
   {
      $user = Auth::user();

      if (!$user || !$this->gate->requiresCode($user, $peringkat)) {
         return $next($request);
      }

      // For other kerja types this borang is a middle step, not the final Hantar.
      if ($jenisKerja !== null && $this->resolveTenderFromRequest($request)?->getKerjaClassification() !== $jenisKerja) {
         return $next($request);
      }

      $twoFactor = $user->twoFactorAuth;

      if (!$twoFactor || !$twoFactor->isConfirmed()) {
         return response()->json([
            'two_factor' => 'setup_required',
            'message'    => 'Penghantaran ini memerlukan pengesahan dua faktor. Sila aktifkan pengesahan dua faktor anda terlebih dahulu.',
            'setup_url'  => route('2fa.setup'),
         ], 428);
      }

      $code = (string) $request->header('X-Two-Factor-Code', '');

      if (trim($code) === '') {
         return response()->json([
            'two_factor' => 'required',
            'message'    => 'Sila masukkan kod pengesahan dua faktor untuk meneruskan penghantaran.',
         ], 428);
      }

      $result = $this->gate->verify($user, $code);

      if (!$result['ok']) {
         return response()->json([
            'two_factor' => $result['error'],
            'message'    => $result['message'],
         ], 422);
      }

      $response = $next($request);

      if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
         try {
            TwoFactorPenilaianLog::create([
               'user_id'    => $user->id,
               'tender_id'  => $this->resolveTenderFromRequest($request)?->id,
               'peringkat'  => $peringkat,
               'kaedah'     => $result['kaedah'],
               'ip_address' => $request->ip(),
               'user_agent' => substr((string) $request->userAgent(), 0, 255),
               'created_at' => now(),
            ]);
         } catch (\Throwable $e) {
            // The Hantar already succeeded; a failed log write must not turn it into an error.
            Log::error('Gagal merekod log 2FA penilaian.', ['user_id' => $user->id, 'error' => $e->getMessage()]);
         }
      }

      return $response;
   }

   /**
    * Path regexes of every route carrying this middleware, for public/js/two-factor-penilaian.js.
    *
    * @return string[]
    */
   public static function protectedPathPatterns(): array
   {
      static $patterns = null;

      if ($patterns !== null) {
         return $patterns;
      }

      $base = rtrim((string) parse_url(url('/'), PHP_URL_PATH), '/');
      $patterns = [];

      foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
         $usesGate = collect($route->middleware())->contains(fn ($m) => is_string($m) && str_starts_with($m, '2fa.penilaian'));

         if (!$usesGate || !in_array('POST', $route->methods(), true)) {
            continue;
         }

         $path = preg_quote($base . '/' . ltrim($route->uri(), '/'), '/');
         $patterns[] = '^' . preg_replace('/\\\\\{[^}]+\\\\\}/', '.+', $path) . '$';
      }

      return $patterns;
   }

   /** Tender may come from a {tender}/{tender_no} route parameter or the tender/tender_id input. */
   protected function resolveTenderFromRequest(Request $request): ?Tender
   {
      $routeTender = $request->route('tender') ?? $request->route('tender_no');

      if ($routeTender instanceof Tender) {
         return $routeTender;
      }

      return $this->resolveTenderByIdentifier(
         $routeTender ?? $request->input('tender', $request->input('tender_id'))
      );
   }
}
