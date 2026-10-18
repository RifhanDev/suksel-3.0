<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorPenilaianRoleKategoriAgensi extends Model
{
   protected $table = 'two_factor_penilaian_role_kategori_agensi';

   protected $fillable = [
      'role_id',
      'organization_type_id',
   ];

   public function organizationType()
   {
      return $this->belongsTo('App\OrganizationType', 'organization_type_id');
   }
}
