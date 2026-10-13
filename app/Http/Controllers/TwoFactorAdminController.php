<?php

namespace App\Http\Controllers;

use App\Role;
use App\TwoFactorAuditLog;
use App\TwoFactorAuth;
use App\TwoFactorRecoveryCode;
use App\TwoFactorRoleSetting;
use App\TwoFactorSetting;
use App\User;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TwoFactorAdminController extends Controller
{
   /**
    * Management page. Also serves the user-enrolment DataTable over ajax.
    */
   public function index(Request $request)
   {
      if ($request->ajax()) {
         $users = User::query()
            ->leftJoin('two_factor_auths', 'two_factor_auths.user_id', '=', 'users.id')
            ->select([
               'users.id',
               'users.name',
               'users.email',
               'two_factor_auths.confirmed_at',
            ]);

         return Datatables::of($users)
            ->addColumn('roles', function ($user) {
               $names = User::find($user->id)->roles->pluck('display_name', 'name');

               if ($names->isEmpty()) {
                  return '<span class="text-muted small">Tiada peranan</span>';
               }

               return collect($names)->map(function ($display, $name) {
                  return '<span class="badge bg-light text-dark border me-1">' . e($display ?: $name) . '</span>';
               })->implode('');
            })
            ->addColumn('status', function ($user) {
               return $user->confirmed_at
                  ? '<span class="badge bg-success">Aktif</span>'
                  : '<span class="badge bg-secondary">Tidak Aktif</span>';
            })
            ->editColumn('confirmed_at', function ($user) {
               return $user->confirmed_at
                  ? \Carbon\Carbon::parse($user->confirmed_at)->format('d/m/Y H:i')
                  : '<span class="text-muted small">&mdash;</span>';
            })
            ->addColumn('actions', function ($user) {
               if (!$user->confirmed_at) {
                  return '<span class="text-muted small">&mdash;</span>';
               }

               return '<form action="' . route('two-factor.users.reset', $user->id) . '" method="POST" class="d-inline m-0">'
                  . csrf_field()
                  . method_field('PUT')
                  . '<button type="submit" class="btn btn-sm btn-danger rounded-8 px-3"'
                  . ' onclick="return confirm(\'Tetapkan semula 2FA untuk pengguna ini? Mereka perlu mendaftar semula.\');">'
                  . 'Set Semula</button>'
                  . '</form>';
            })
            ->rawColumns(['roles', 'status', 'confirmed_at', 'actions'])
            ->make(true);
      }

      return view('two-factor.index', [
         'roles'           => Role::orderBy('name')->get(),
         'requiredRoleIds' => TwoFactorRoleSetting::requiredRoleIds(),
         'settings'        => TwoFactorSetting::current(),
      ]);
   }

   /**
    * Save which roles require 2FA. Roles absent from the payload are switched off.
    */
   public function updateRoleSettings(Request $request)
   {
      $selected = array_map('intval', (array) $request->input('required_roles', []));
      $actorId  = auth()->id();
      $changes  = [];

      DB::transaction(function () use ($selected, $actorId, &$changes) {
         foreach (Role::all() as $role) {
            $setting = TwoFactorRoleSetting::firstOrNew(['role_id' => $role->id]);
            $wasRequired = (bool) $setting->required;
            $nowRequired = in_array($role->id, $selected, true);

            if ($wasRequired === $nowRequired && $setting->exists) {
               continue;
            }

            $setting->required = $nowRequired;
            $setting->updated_by = $actorId;
            $setting->save();

            if ($wasRequired !== $nowRequired) {
               $changes[] = [
                  'role' => $role->name,
                  'from' => $wasRequired,
                  'to'   => $nowRequired,
               ];
            }
         }

         if (!empty($changes) && $actorId) {
            TwoFactorAuditLog::record($actorId, TwoFactorAuditLog::EVENT_ROLE_TOGGLED, $actorId, [
               'changes' => $changes,
            ]);
         }
      });

      return redirect()->route('two-factor.index')
         ->with('success', 'Tetapan peranan 2FA telah dikemaskini.');
   }

   /**
    * Save the global knobs (grace period, recovery code count, lockout).
    */
   public function updateSettings(Request $request)
   {
      $data = $request->validate([
         'grace_period_days'    => 'required|integer|min:0|max:365',
         'recovery_codes_count' => 'required|integer|min:1|max:20',
         'max_failed_attempts'  => 'required|integer|min:1|max:20',
         'lockout_minutes'      => 'required|integer|min:1|max:1440',
         'remember_device_days' => 'required|integer|min:1|max:365',
      ]);

      $settings = TwoFactorSetting::current();
      $settings->fill($data);
      $settings->updated_by = auth()->id();
      $settings->save();

      return redirect()->route('two-factor.index')
         ->with('success', 'Tetapan umum 2FA telah dikemaskini.');
   }

   /**
    * Lockout recovery: wipe a user's enrolment so they start over on next login.
    * Deleting the row also clears required_since, giving them a fresh grace period.
    */
   public function resetUser($userId)
   {
      $user = User::findOrFail($userId);

      DB::transaction(function () use ($user) {
         TwoFactorRecoveryCode::where('user_id', $user->id)->delete();
         TwoFactorAuth::where('user_id', $user->id)->delete();

         TwoFactorAuditLog::record(
            $user->id,
            TwoFactorAuditLog::EVENT_ADMIN_RESET,
            auth()->id()
         );
      });

      return redirect()->route('two-factor.index')
         ->with('success', 'Pengesahan dua faktor untuk ' . $user->name . ' telah ditetapkan semula.');
   }

   /**
    * Audit log DataTable (ajax only).
    */
   public function audit(Request $request)
   {
      $logs = TwoFactorAuditLog::query()
         ->with(['user', 'actor'])
         ->select('two_factor_audit_logs.*')
         ->orderByDesc('created_at');

      return Datatables::of($logs)
         ->editColumn('created_at', function ($log) {
            return $log->created_at ? $log->created_at->format('d/m/Y H:i') : '&mdash;';
         })
         ->addColumn('user_name', function ($log) {
            return $log->user ? e($log->user->name) : '<span class="text-muted small">&mdash;</span>';
         })
         ->addColumn('actor_name', function ($log) {
            return $log->actor
               ? e($log->actor->name)
               : '<span class="text-muted small">Sistem / Sendiri</span>';
         })
         ->editColumn('event', function ($log) {
            $labels = [
               TwoFactorAuditLog::EVENT_ENROLLED      => ['Didaftarkan', 'bg-success'],
               TwoFactorAuditLog::EVENT_DISABLED      => ['Dimatikan', 'bg-secondary'],
               TwoFactorAuditLog::EVENT_ADMIN_RESET   => ['Set Semula (Admin)', 'bg-danger'],
               TwoFactorAuditLog::EVENT_RECOVERY_USED => ['Kod Pemulihan Digunakan', 'bg-warning text-dark'],
               TwoFactorAuditLog::EVENT_LOCKED_OUT    => ['Dikunci', 'bg-danger'],
               TwoFactorAuditLog::EVENT_ROLE_TOGGLED  => ['Tetapan Peranan Diubah', 'bg-info text-dark'],
            ];

            [$label, $class] = $labels[$log->event] ?? [$log->event, 'bg-light text-dark'];

            return '<span class="badge ' . $class . '">' . e($label) . '</span>';
         })
         ->addColumn('meta_summary', function ($log) {
            $meta = $log->meta;

            if (empty($meta)) {
               return '<span class="text-muted small">&mdash;</span>';
            }

            switch ($log->event) {
               case TwoFactorAuditLog::EVENT_ROLE_TOGGLED:
                  $lines = array_map(function ($change) {
                     $state = !empty($change['to']) ? 'diwajibkan' : 'tidak lagi diwajibkan';
                     return 'Peranan <strong>' . e($change['role'] ?? '?') . '</strong>: 2FA ' . $state;
                  }, $meta['changes'] ?? []);

                  return implode('<br>', $lines) ?: '<span class="text-muted small">&mdash;</span>';

               case TwoFactorAuditLog::EVENT_RECOVERY_USED:
                  return 'Baki kod pemulihan selepas ini: <strong>' . (int) ($meta['remaining'] ?? 0) . '</strong>';

               case TwoFactorAuditLog::EVENT_LOCKED_OUT:
                  $until = $meta['locked_until'] ?? null;
                  $formatted = $until ? \Carbon\Carbon::parse($until)->format('d/m/Y H:i') : '&mdash;';
                  return 'Dikunci sehingga ' . $formatted;

               default:
                  // Fallback for any future event type: still readable, not raw JSON.
                  $parts = [];
                  foreach ($meta as $key => $value) {
                     $parts[] = e($key) . ': ' . e(is_scalar($value) ? $value : json_encode($value));
                  }
                  return implode(', ', $parts);
            }
         })
         ->rawColumns(['user_name', 'actor_name', 'event', 'meta_summary', 'created_at'])
         ->make(true);
   }
}
