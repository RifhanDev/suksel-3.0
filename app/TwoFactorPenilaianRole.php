<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TwoFactorPenilaianRole extends Model
{
   protected $table = 'two_factor_penilaian_roles';

   protected $fillable = [
      'role_id',
      'semua_agensi',
      'updated_by',
   ];

   protected function casts(): array
   {
      return [
         'semua_agensi' => 'boolean',
      ];
   }

   /**
    * Roles holding at least one penilaian stage permission, each tagged with the stages it can open.
    */
   public static function eligibleRoles(): Collection
   {
      $eligible = collect();

      foreach (Role::with('perms')->orderBy('name')->get() as $role) {
         // Admin bypasses penilaian checks; it is not an evaluator.
         if ($role->name === 'Admin') {
            continue;
         }

         $permissionNames = $role->perms->pluck('name');

         $peringkat = collect(TwoFactorPenilaianSetting::PERMISSION)
            ->filter(fn ($permission) => $permissionNames->contains($permission))
            ->keys()
            ->all();

         if (!empty($peringkat)) {
            $role->peringkat = $peringkat;
            $eligible->push($role);
         }
      }

      return $eligible;
   }

   public function role()
   {
      return $this->belongsTo('App\Role', 'role_id');
   }

   public function kategoriAgensi()
   {
      return $this->hasMany('App\TwoFactorPenilaianRoleKategoriAgensi', 'role_id', 'role_id');
   }

   public function agensi()
   {
      return $this->hasMany('App\TwoFactorPenilaianRoleAgensi', 'role_id', 'role_id');
   }
}
