<?php

namespace App\Http\Controllers;

use App\TwoFactorAuditLog;
use App\TwoFactorAuth;
use App\TwoFactorRecoveryCode;
use App\TwoFactorSetting;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
   /**
    * Show the enrolment page: QR code plus the manual-entry key.
    */
   public function setup()
   {
      $user = auth()->user();

      if ($user->hasTwoFactorEnabled()) {
         return redirect()->route('2fa.manage')
            ->with('info', 'Pengesahan dua faktor sudah aktif untuk akaun anda.');
      }

      $google2fa = new Google2FA();

      $twoFactor = TwoFactorAuth::firstOrNew(['user_id' => $user->id]);

      // Reuse a pending secret so refreshing the page does not invalidate a QR
      // the user has already scanned.
      if (empty($twoFactor->secret)) {
         $twoFactor->secret = $google2fa->generateSecretKey();
         $twoFactor->user_id = $user->id;
         $twoFactor->save();
      }

      // The date in the account name tells a new app entry apart from an old one after a reset.
      $qrCodeUrl = $google2fa->getQRCodeUrl(
         config('app.name', 'e-Perolehan Selangor'),
         $user->email . ' (' . now()->format('d/m/Y') . ')',
         $twoFactor->secret
      );

      $renderer = new ImageRenderer(new RendererStyle(240), new SvgImageBackEnd());
      $qrCodeSvg = (new Writer($renderer))->writeString($qrCodeUrl);

      return view('auth.2fa-setup', [
         'qrCodeSvg' => $qrCodeSvg,
         'secret'    => $twoFactor->secret,
         // Turned off or reset before: their app may still hold an entry for the old key.
         'hadTwoFactorBefore' => TwoFactorAuditLog::where('user_id', $user->id)
            ->whereIn('event', [TwoFactorAuditLog::EVENT_DISABLED, TwoFactorAuditLog::EVENT_ADMIN_RESET])
            ->exists(),
      ]);
   }

   /**
    * Verify the first code, mark enrolment confirmed and issue recovery codes.
    */
   public function confirm(Request $request)
   {
      $request->validate([
         'code' => 'required|string|size:6',
      ]);

      $user = auth()->user();
      $twoFactor = $user->twoFactorAuth;

      if (!$twoFactor || empty($twoFactor->secret)) {
         return redirect()->route('2fa.setup')
            ->with('error', 'Sesi pendaftaran telah tamat. Sila imbas kod QR semula.');
      }

      // verifyKeyNewer returns the code's time step, saved so the same code cannot be replayed at login/Hantar.
      $step = (new Google2FA())->verifyKeyNewer($twoFactor->secret, trim($request->input('code')), (int) ($twoFactor->last_used_step ?? 0));

      if ($step === false) {
         return redirect()->route('2fa.setup')
            ->with('error', 'Kod pengesahan tidak betul. Sila cuba lagi.');
      }

      $plainCodes = [];

      DB::transaction(function () use ($user, $twoFactor, $step, &$plainCodes) {
         $twoFactor->last_used_step = $step;
         $twoFactor->confirmed_at = now();
         $twoFactor->failed_attempts = 0;
         $twoFactor->locked_until = null;
         $twoFactor->save();

         $plainCodes = $this->regenerateRecoveryCodes($user->id);

         TwoFactorAuditLog::record($user->id, TwoFactorAuditLog::EVENT_ENROLLED);
      });

      // Plaintext codes exist only for this one render - never persisted.
      return view('auth.2fa-recovery-codes', [
         'codes' => $plainCodes,
      ]);
   }

   /**
    * Self-service page: status, and the option to regenerate recovery codes.
    */
   public function manage()
   {
      $user = auth()->user();
      $twoFactor = $user->twoFactorAuth;

      return view('auth.2fa-manage', [
         'twoFactor'     => $twoFactor,
         'remainingCodes' => TwoFactorRecoveryCode::where('user_id', $user->id)->unused()->count(),
         'isRequired'    => $user->requiresTwoFactor(),
         // Activation deadline, shown on the "required" notice while 2FA is not active yet.
         'deadline'      => $twoFactor && $twoFactor->required_since
            ? $twoFactor->required_since->copy()->addDays(TwoFactorSetting::current()->grace_period_days)
            : null,
      ]);
   }

   /**
    * Issue a fresh batch of recovery codes, invalidating the old ones.
    */
   public function regenerate()
   {
      $user = auth()->user();

      if (!$user->hasTwoFactorEnabled()) {
         return redirect()->route('2fa.setup');
      }

      $codes = $this->regenerateRecoveryCodes($user->id);

      return view('auth.2fa-recovery-codes', [
         'codes' => $codes,
      ]);
   }

   /**
    * Turn 2FA off. Blocked when the user's role makes it mandatory.
    */
   public function disable()
   {
      $user = auth()->user();

      if ($user->requiresTwoFactor()) {
         return redirect()->route('2fa.manage')
            ->with('error', 'Peranan anda memerlukan pengesahan dua faktor. Ia tidak boleh dimatikan.');
      }

      DB::transaction(function () use ($user) {
         TwoFactorRecoveryCode::where('user_id', $user->id)->delete();
         TwoFactorAuth::where('user_id', $user->id)->delete();

         TwoFactorAuditLog::record($user->id, TwoFactorAuditLog::EVENT_DISABLED);
      });

      return redirect()->route('2fa.manage')
         ->with('success', 'Pengesahan dua faktor telah dimatikan.');
   }

   /**
    * Replaces all recovery codes for a user and returns the plaintext batch.
    * Only hashes are stored.
    */
   protected function regenerateRecoveryCodes($userId): array
   {
      $count = TwoFactorSetting::current()->recovery_codes_count;

      TwoFactorRecoveryCode::where('user_id', $userId)->delete();

      $plainCodes = [];

      for ($i = 0; $i < $count; $i++) {
         $code = strtoupper(Str::random(4) . '-' . Str::random(4));
         $plainCodes[] = $code;

         TwoFactorRecoveryCode::create([
            'user_id'    => $userId,
            'code_hash'  => Hash::make($code),
            'created_at' => now(),
         ]);
      }

      return $plainCodes;
   }
}
