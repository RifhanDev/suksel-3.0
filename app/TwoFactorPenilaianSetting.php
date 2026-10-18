<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorPenilaianSetting extends Model
{
   protected $table = 'two_factor_penilaian_settings';

   // Penilaian stages whose final Hantar can ask for a 2FA code.
   const PERINGKAT = [
      'pembuka'  => 'Jawatankuasa Pembuka',
      'cut_off'  => 'Cut-Off',
      'teknikal' => 'Penilaian Teknikal',
      'kewangan' => 'Penilaian Kewangan',
   ];

   // Menu permission that opens each stage's page.
   const PERMISSION = [
      'pembuka'  => 'OpenerEvaluation:list',
      'cut_off'  => 'CutOff:list',
      'teknikal' => 'TechnicalEvaluation:list',
      'kewangan' => 'FinancialEvaluation:list',
   ];

   protected $fillable = [
      'peringkat',
      'required',
      'updated_by',
   ];

   protected function casts(): array
   {
      return [
         'required' => 'boolean',
      ];
   }

   public static function isRequired(string $peringkat): bool
   {
      return self::where('peringkat', $peringkat)->where('required', true)->exists();
   }

   /** @return string[] peringkat keys currently switched on */
   public static function requiredPeringkat(): array
   {
      return self::where('required', true)->pluck('peringkat')->all();
   }
}
