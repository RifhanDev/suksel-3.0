<?php

namespace App\Http\Controllers;

use App\OrganizationType;
use App\OrganizationUnit;
use App\Role;
use App\TwoFactorAuditLog;
use App\TwoFactorAuth;
use App\TwoFactorPenilaianLog;
use App\TwoFactorPenilaianRole;
use App\TwoFactorPenilaianRoleAgensi;
use App\TwoFactorPenilaianRoleKategoriAgensi;
use App\TwoFactorPenilaianSetting;
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
               $names = User::find($user->id)->roles->map(fn ($role) => $role->display_name ?: $role->name);

               if ($names->isEmpty()) {
                  return '<span class="text-muted small">Tiada peranan</span>';
               }

               return '<span class="small">' . e($names->implode(', ')) . '</span>';
            })
            ->addColumn('status', function ($user) {
               return $user->confirmed_at
                  ? '<span class="badge-status badge-status-success">Diaktifkan</span>'
                  : '<span class="badge-status badge-status-warning">Belum Diaktifkan</span>';
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
                  . '<button type="button" class="btn btn-sm btn-warning rounded-8 px-3 text-nowrap js-tf-reset" data-name="' . e($user->name) . '">Set Semula</button>'
                  . '</form>';
            })
            ->rawColumns(['roles', 'status', 'confirmed_at', 'actions'])
            ->make(true);
      }

      return view('two-factor.index', [
         'roles'           => Role::orderBy('name')->get(),
         'requiredRoleIds' => TwoFactorRoleSetting::requiredRoleIds(),
         'settings'        => TwoFactorSetting::current(),
         // 2FA at penilaian Hantar
         'penilaianPeringkat' => TwoFactorPenilaianSetting::PERINGKAT,
         'penilaianRequired'  => TwoFactorPenilaianSetting::requiredPeringkat(),
         'penilaianEligible'  => TwoFactorPenilaianRole::eligibleRoles(),
         'penilaianRoles'     => TwoFactorPenilaianRole::with(['kategoriAgensi', 'agensi'])->get()->keyBy('role_id'),
         'kategoriAgensi'     => OrganizationType::orderBy('sort_no')->orderBy('name')->get(['id', 'name']),
         'agensiByKategori'   => OrganizationUnit::query()->orderBy('name')->get(['id', 'name', 'short_name', 'type_id'])->groupBy('type_id'),
      ]);
   }

   /**
    * Save which penilaian stages ask for a 2FA code at Hantar, and for which roles / kategori agensi / agensi.
    */
   public function updatePenilaianSettings(Request $request)
   {
      $data = $request->validate([
         'peringkat'    => 'nullable|array',
         'peringkat.*'  => 'string|in:' . implode(',', array_keys(TwoFactorPenilaianSetting::PERINGKAT)),
         'roles'        => 'nullable|array',
         'roles.*'      => 'integer|exists:roles,id',
         'skop'         => 'nullable|array',
         'skop.*'       => 'in:semua,pilih',
         'kategori'     => 'nullable|array',
         'kategori.*'   => 'array',
         'kategori.*.*' => 'integer|exists:organization_types,id',
         'agensi'       => 'nullable|array',
         'agensi.*'     => 'array',
         'agensi.*.*'   => 'integer|exists:organization_units,id',
      ]);

      $onPeringkat = $data['peringkat'] ?? [];

      // A role is kept only if it can open at least one of the ticked stages and requires 2FA at login.
      $selectedIds = array_map('intval', $data['roles'] ?? []);
      $loginRequiredIds = TwoFactorRoleSetting::requiredRoleIds();
      $roleIds = [];

      foreach (TwoFactorPenilaianRole::eligibleRoles() as $role) {
         $reachesTickedStage = !empty(array_intersect($role->peringkat, $onPeringkat));

         if (in_array($role->id, $selectedIds, true) && $reachesTickedStage && in_array($role->id, $loginRequiredIds)) {
            $roleIds[] = $role->id;
         }
      }

      $roleNames = Role::whereIn('id', $roleIds)->pluck('name', 'id');

      // "Pilih agensi" with nothing picked would silently ask nobody; refuse it instead.
      foreach ($roleIds as $roleId) {
         $pilih = ($data['skop'][$roleId] ?? 'semua') === 'pilih';

         if ($pilih && empty($data['kategori'][$roleId]) && empty($data['agensi'][$roleId])) {
            return redirect()->to(route('two-factor.index') . '#tab-penilaian')
               ->with('error', 'Sila pilih sekurang-kurangnya satu kategori agensi atau agensi untuk peranan ' . ($roleNames[$roleId] ?? $roleId) . '.');
         }
      }

      $actorId = auth()->id();
      $before  = $this->penilaianSummary();

      DB::transaction(function () use ($onPeringkat, $roleIds, $data, $actorId) {
         foreach (array_keys(TwoFactorPenilaianSetting::PERINGKAT) as $peringkat) {
            $setting = TwoFactorPenilaianSetting::firstOrNew(['peringkat' => $peringkat]);
            $setting->required = in_array($peringkat, $onPeringkat, true);
            $setting->updated_by = $actorId;
            $setting->save();
         }

         // Unticked roles lose their whole selection.
         TwoFactorPenilaianRole::whereNotIn('role_id', $roleIds ?: [0])->delete();
         TwoFactorPenilaianRoleKategoriAgensi::whereNotIn('role_id', $roleIds ?: [0])->delete();
         TwoFactorPenilaianRoleAgensi::whereNotIn('role_id', $roleIds ?: [0])->delete();

         foreach ($roleIds as $roleId) {
            $semua = ($data['skop'][$roleId] ?? 'semua') === 'semua';

            $rule = TwoFactorPenilaianRole::firstOrNew(['role_id' => $roleId]);
            $rule->semua_agensi = $semua;
            $rule->updated_by = $actorId;
            $rule->save();

            TwoFactorPenilaianRoleKategoriAgensi::where('role_id', $roleId)->delete();
            TwoFactorPenilaianRoleAgensi::where('role_id', $roleId)->delete();

            if ($semua) {
               continue;
            }

            foreach (array_unique(array_map('intval', $data['kategori'][$roleId] ?? [])) as $typeId) {
               TwoFactorPenilaianRoleKategoriAgensi::create(['role_id' => $roleId, 'organization_type_id' => $typeId]);
            }

            foreach (array_unique(array_map('intval', $data['agensi'][$roleId] ?? [])) as $unitId) {
               TwoFactorPenilaianRoleAgensi::create(['role_id' => $roleId, 'organization_unit_id' => $unitId]);
            }
         }
      });

      $this->recordPenilaianChanges($before, $actorId);

      return redirect()->to(route('two-factor.index') . '#tab-penilaian')
         ->with('success', 'Tetapan 2FA semasa Hantar penilaian telah dikemaskini.');
   }

   /**
    * Audits what changed in the penilaian settings since $before (a penilaianSummary() snapshot).
    */
   private function recordPenilaianChanges(array $before, ?int $actorId): void
   {
      $after   = $this->penilaianSummary();
      $changes = [];

      foreach (['peringkat', 'roles'] as $group) {
         foreach (array_unique(array_merge(array_keys($before[$group]), array_keys($after[$group]))) as $key) {
            $from = $before[$group][$key] ?? 'Tidak';
            $to   = $after[$group][$key] ?? 'Tidak';

            if ($from !== $to) {
               $changes[] = ['jenis' => $group, 'nama' => $key, 'from' => $from, 'to' => $to];
            }
         }
      }

      if (!empty($changes) && $actorId) {
         TwoFactorAuditLog::record($actorId, TwoFactorAuditLog::EVENT_PENILAIAN_TOGGLED, $actorId, [
            'changes' => $changes,
         ]);
      }
   }

   /**
    * Readable snapshot of the penilaian settings, used to diff for the audit log.
    *
    * @return array{peringkat: array<string, string>, roles: array<string, string>}
    */
   private function penilaianSummary(): array
   {
      $peringkat = [];
      foreach (TwoFactorPenilaianSetting::all() as $setting) {
         $peringkat[TwoFactorPenilaianSetting::PERINGKAT[$setting->peringkat] ?? $setting->peringkat] = $setting->required ? 'Ya' : 'Tidak';
      }

      $roles = [];
      foreach (TwoFactorPenilaianRole::with(['role', 'kategoriAgensi.organizationType', 'agensi.organizationUnit'])->get() as $rule) {
         $name = $rule->role->name ?? ('#' . $rule->role_id);

         if ($rule->semua_agensi) {
            $roles[$name] = 'Semua agensi';
            continue;
         }

         // toBase(): merge() on an empty Eloquent collection expects models, not these label strings.
         $scope = $rule->kategoriAgensi->toBase()->map(fn ($k) => 'Kategori ' . ($k->organizationType->name ?? $k->organization_type_id))
            ->merge($rule->agensi->map(fn ($a) => $a->organizationUnit->name ?? ('#' . $a->organization_unit_id)))
            ->sort()
            ->implode(', ');

         $roles[$name] = $scope;
      }

      return ['peringkat' => $peringkat, 'roles' => $roles];
   }

   /** One readable audit row: "Peranan · Agency User: Tidak ditanda → Aktif". */
   private function auditLine(string $type, string $name, ?string $from, string $to): string
   {
      $change = $from !== null
         ? '<span class="text-muted">' . e($from) . '</span> <span class="text-muted">&rarr;</span> <span class="fw-semibold">' . e($to) . '</span>'
         : '<span class="fw-semibold">' . e($to) . '</span>';

      return '<div class="small py-1">'
         . '<span class="text-muted">' . e($type) . ' &middot;</span> ' . e($name) . ': ' . $change
         . '</div>';
   }

   /** Audit row for a role's agency list change: what was added/removed, then the full list after it. */
   private function auditListLine(string $name, array $added, array $removed, array $current): string
   {
      $row = fn (string $label, array $items, string $class = '') => '<div class="d-flex gap-2 ms-3">'
         . '<span class="text-muted tf-audit-label">' . $label . '</span>'
         . '<span class="' . $class . '">' . e(implode(', ', $items)) . '</span>'
         . '</div>';

      return '<div class="small py-1"><span class="text-muted">Peranan &middot;</span> ' . e($name)
         . ($added ? $row('Ditambah', $added, 'fw-semibold') : '')
         . ($removed ? $row('Dibuang', $removed, 'fw-semibold') : '')
         . $row('Semasa', $current)
         . '</div>';
   }

   /**
    * Log of codes accepted at penilaian Hantar (ajax only).
    */
   public function penilaianLogs(Request $request)
   {
      $logs = TwoFactorPenilaianLog::query()
         ->with(['user', 'tender'])
         ->select('two_factor_penilaian_logs.*')
         ->orderByDesc('created_at');

      return Datatables::of($logs)
         ->editColumn('created_at', function ($log) {
            return $log->created_at ? $log->created_at->format('d/m/Y H:i') : '&mdash;';
         })
         ->addColumn('user_name', function ($log) {
            return $log->user ? e($log->user->name) : '<span class="text-muted small">&mdash;</span>';
         })
         ->addColumn('tender_no', function ($log) {
            $no = $log->tender ? ($log->tender->no_tender ?: $log->tender->ref_number) : null;
            return $no ? e($no) : '<span class="text-muted small">&mdash;</span>';
         })
         ->editColumn('peringkat', function ($log) {
            return e(TwoFactorPenilaianSetting::PERINGKAT[$log->peringkat] ?? $log->peringkat);
         })
         ->editColumn('kaedah', function ($log) {
            return $log->kaedah === 'recovery'
               ? '<span class="badge-status badge-status-warning">Kod Pemulihan</span>'
               : '<span class="badge-status badge-status-info">Aplikasi Pengesah</span>';
         })
         ->rawColumns(['created_at', 'user_name', 'tender_no', 'kaedah'])
         ->make(true);
   }

   /**
    * Save which roles require 2FA. Roles absent from the payload are switched off.
    */
   public function updateRoleSettings(Request $request)
   {
      $selected = array_map('intval', (array) $request->input('required_roles', []));
      $actorId  = auth()->id();
      $changes  = [];
      $penilaianBefore = $this->penilaianSummary();

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

         // Penilaian 2FA sits on top of 2FA login, so a role switched off here loses its penilaian setting too.
         TwoFactorPenilaianRole::whereNotIn('role_id', $selected ?: [0])->delete();
         TwoFactorPenilaianRoleKategoriAgensi::whereNotIn('role_id', $selected ?: [0])->delete();
         TwoFactorPenilaianRoleAgensi::whereNotIn('role_id', $selected ?: [0])->delete();
      });

      $this->recordPenilaianChanges($penilaianBefore, $actorId);

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

      return redirect()->to(route('two-factor.index') . '#tab-pengguna')
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
               TwoFactorAuditLog::EVENT_ENROLLED          => ['Diaktifkan', 'badge-status-success'],
               TwoFactorAuditLog::EVENT_DISABLED          => ['Dimatikan', 'badge-status-warning'],
               TwoFactorAuditLog::EVENT_ADMIN_RESET       => ['Set Semula (Admin)', 'badge-status-warning badge-icon-refresh'],
               TwoFactorAuditLog::EVENT_RECOVERY_USED     => ['Kod Pemulihan Digunakan', 'badge-status-warning'],
               TwoFactorAuditLog::EVENT_LOCKED_OUT        => ['Dikunci', 'badge-status-danger'],
               TwoFactorAuditLog::EVENT_ROLE_TOGGLED      => ['Tetapan Log Masuk Diubah', 'badge-status-info'],
               TwoFactorAuditLog::EVENT_PENILAIAN_TOGGLED => ['Tetapan Penilaian Diubah', 'badge-status-info'],
            ];

            [$label, $class] = $labels[$log->event] ?? [$log->event, 'badge-status-info'];

            return '<span class="badge-status ' . $class . '">' . e($label) . '</span>';
         })
         ->addColumn('meta_summary', function ($log) {
            $meta = $log->meta;

            if (empty($meta)) {
               return '<span class="text-muted small">&mdash;</span>';
            }

            switch ($log->event) {
               case TwoFactorAuditLog::EVENT_ROLE_TOGGLED:
                  $lines = array_map(function ($change) {
                     $state = !empty($change['to']) ? 'Wajib 2FA' : 'Tidak wajib 2FA';
                     return $this->auditLine('Peranan', $change['role'] ?? '?', null, $state);
                  }, $meta['changes'] ?? []);

                  return implode('', $lines) ?: '<span class="text-muted small">&mdash;</span>';

               case TwoFactorAuditLog::EVENT_PENILAIAN_TOGGLED:
                  $lines = array_map(function ($change) {
                     $isRole = ($change['jenis'] ?? '') === 'roles';
                     $name   = is_string($change['nama'] ?? null) ? $change['nama'] : '?';
                     $from   = is_string($change['from'] ?? null) ? $change['from'] : '?';
                     $to     = is_string($change['to'] ?? null) ? $change['to'] : '?';

                     // Between two agency lists, show only what was added and removed, not both full lists.
                     $singleValues = ['Ya', 'Tidak', 'Semua agensi', '?'];
                     if ($isRole && !in_array($from, $singleValues, true) && !in_array($to, $singleValues, true)) {
                        $old = explode(', ', $from);
                        $new = explode(', ', $to);
                        $added   = array_diff($new, $old);
                        $removed = array_diff($old, $new);

                        if ($added || $removed) {
                           return $this->auditListLine($name, $added, $removed, $new);
                        }
                     }

                     // Stored as Ya/Tidak; shown as words that read naturally per row type.
                     $readable = fn ($value) => match ($value) {
                        'Ya'    => 'Aktif',
                        'Tidak' => $isRole ? 'Tidak ditanda' : 'Tidak aktif',
                        default => $value,
                     };

                     return $this->auditLine($isRole ? 'Peranan' : 'Peringkat', $name, $readable($from), $readable($to));
                  }, is_array($meta['changes'] ?? null) ? $meta['changes'] : []);

                  return implode('', $lines) ?: '<span class="text-muted small">&mdash;</span>';

               case TwoFactorAuditLog::EVENT_RECOVERY_USED:
                  return '<span class="small">Baki kod pemulihan: <span class="fw-semibold">' . (int) ($meta['remaining'] ?? 0) . '</span></span>';

               case TwoFactorAuditLog::EVENT_LOCKED_OUT:
                  $until = $meta['locked_until'] ?? null;
                  $formatted = $until ? \Carbon\Carbon::parse($until)->format('d/m/Y H:i') : '&mdash;';
                  return '<span class="small">Dikunci sehingga <span class="fw-semibold">' . $formatted . '</span></span>';

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
