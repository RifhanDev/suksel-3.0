<?php

namespace App\Services;

use App\OrganizationUnit;
use App\TwoFactorAuditLog;
use App\TwoFactorPenilaianRole;
use App\TwoFactorPenilaianSetting;
use App\TwoFactorRecoveryCode;
use App\TwoFactorRoleSetting;
use App\TwoFactorSetting;
use App\User;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

/**
 * Decides whether a user must enter a 2FA code at a penilaian stage's final Hantar,
 * and checks that code. Verification mirrors AuthController::verify2FA so login and
 * Hantar share one lockout counter and one last-used TOTP step.
 */
class TwoFactorPenilaianGate
{
   public function requiresCode(User $user, string $peringkat): bool
   {
      if (!TwoFactorPenilaianSetting::isRequired($peringkat)) {
         return false;
      }

      // Penilaian is an extra layer on top of 2FA login, so only roles that require 2FA at login count.
      $roleIds = array_intersect($user->roles()->pluck('roles.id')->all(), TwoFactorRoleSetting::requiredRoleIds());

      if (empty($roleIds)) {
         return false;
      }

      $rules = TwoFactorPenilaianRole::query()
         ->whereIn('role_id', $roleIds)
         ->with(['kategoriAgensi', 'agensi'])
         ->get();

      if ($rules->isEmpty()) {
         return false;
      }

      $unitId = $user->organization_unit_id ? (int) $user->organization_unit_id : null;
      $typeId = $unitId ? (int) OrganizationUnit::query()->where('id', $unitId)->value('type_id') : null;

      foreach ($rules as $rule) {
         if ($rule->semua_agensi) {
            return true;
         }

         if ($unitId && $rule->agensi->contains('organization_unit_id', $unitId)) {
            return true;
         }

         if ($typeId && $rule->kategoriAgensi->contains('organization_type_id', $typeId)) {
            return true;
         }
      }

      return false;
   }

   /**
    * @return array{ok: bool, kaedah: ?string, error: ?string, message: ?string}
    */
   public function verify(User $user, string $code): array
   {
      $twoFactor = $user->twoFactorAuth;

      if (!$twoFactor || !$twoFactor->isConfirmed()) {
         return $this->fail('setup_required', 'Sila aktifkan pengesahan dua faktor terlebih dahulu.');
      }

      if ($twoFactor->isLocked()) {
         $minutesLeft = max(1, (int) ceil(now()->diffInMinutes($twoFactor->locked_until)));
         return $this->fail('locked', 'Terlalu banyak percubaan. Sila cuba lagi dalam ' . $minutesLeft . ' minit.');
      }

      $submitted = trim($code);
      $kaedah = null;

      if (preg_match('/^\d{6}$/', $submitted)) {
         // verifyKeyNewer only accepts a step later than the last one used; 0 (not null) makes it return the step.
         $step = (new Google2FA())->verifyKeyNewer($twoFactor->secret, $submitted, (int) ($twoFactor->last_used_step ?? 0));

         if ($step !== false) {
            $twoFactor->last_used_step = $step;
            $kaedah = 'totp';
         }
      } else {
         $candidates = TwoFactorRecoveryCode::where('user_id', $user->id)->unused()->get();

         foreach ($candidates as $candidate) {
            if (Hash::check(strtoupper($submitted), $candidate->code_hash)) {
               $candidate->used_at = now();
               $candidate->save();
               $kaedah = 'recovery';
               break;
            }
         }
      }

      if (!$kaedah) {
         $settings = TwoFactorSetting::current();
         $twoFactor->failed_attempts = $twoFactor->failed_attempts + 1;

         if ($twoFactor->failed_attempts >= $settings->max_failed_attempts) {
            $twoFactor->failed_attempts = 0;
            $twoFactor->locked_until = now()->addMinutes($settings->lockout_minutes);
            $twoFactor->save();

            TwoFactorAuditLog::record($user->id, TwoFactorAuditLog::EVENT_LOCKED_OUT, null, [
               'locked_until' => $twoFactor->locked_until->toDateTimeString(),
               'source'       => 'penilaian',
            ]);

            return $this->fail('locked', 'Terlalu banyak percubaan. Akaun dikunci selama ' . $settings->lockout_minutes . ' minit.');
         }

         $twoFactor->save();

         return $this->fail('invalid', 'Kod pengesahan tidak betul atau telah digunakan.');
      }

      $twoFactor->failed_attempts = 0;
      $twoFactor->locked_until = null;
      $twoFactor->save();

      if ($kaedah === 'recovery') {
         TwoFactorAuditLog::record($user->id, TwoFactorAuditLog::EVENT_RECOVERY_USED, null, [
            'remaining' => TwoFactorRecoveryCode::where('user_id', $user->id)->unused()->count(),
            'source'    => 'penilaian',
         ]);
      }

      return ['ok' => true, 'kaedah' => $kaedah, 'error' => null, 'message' => null];
   }

   private function fail(string $error, string $message): array
   {
      return ['ok' => false, 'kaedah' => null, 'error' => $error, 'message' => $message];
   }
}
