<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorPenilaianRoleAgensi extends Model
{
   protected $table = 'two_factor_penilaian_role_agensi';

   protected $fillable = [
      'role_id',
      'organization_unit_id',
   ];

   public function organizationUnit()
   {
      return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id');
   }
}
