<?php

namespace App\Support;

/**
 * Butang "Lantik Jawatan Kuasa" bagi kolum Tindakan.
 *
 * Dikongsi oleh senarai tender admin (TendersController::index) dan senarai
 * agensi (OrganizationUnitsController::agency). Sebelum ini logik ini hanya
 * wujud dalam senarai admin, dan senarai agensi memaparkan pautan mentah
 * separuh siap di bawah kolum "status" — tanpa logik peringkat dan tanpa modal.
 * Disatukan di sini supaya syaratnya tidak boleh terpesong antara dua skrin.
 *
 * Markup bergantung pada partials/_pilih_peringkat_modal (modal) dan
 * partials/_pilih_peringkat_script (pengendali). Halaman yang memaparkan butang
 * ini mesti menyertakan kedua-duanya.
 */
class CommitteeAction
{
    public static function button($tender): string
    {
        if (! auth()->check()) {
            return '';
        }

        $user = auth()->user();

        // Sokong pemetaan kebenaran gaya Entrust lama dan Spatie.
        $canCreateCommittee = $user->can('committee:create');

        if (! $canCreateCommittee && method_exists($user, 'hasPermissionTo')) {
            try {
                $canCreateCommittee = $user->hasPermissionTo('committee:create');
            } catch (\Throwable $th) {
                $canCreateCommittee = false;
            }
        }

        if (! $canCreateCommittee) {
            return '';
        }

        // Proses sudah bermula atau selesai — tiada butang.
        if ($tender->status !== 'Tiada Jawatan Kuasa') {
            return '';
        }

        $tenderPeringkat = $tender->tender_peringkat ?? null;

        // Peringkat sudah disimpan (draf sedang berjalan) — butang kuning "Sambung".
        if (! empty($tenderPeringkat)) {
            $sambungUrl = ((int) $tenderPeringkat === 1)
                ? route('pelantikanJawatankuasaSatuPeringkat') . '?tender=' . $tender->uuid
                : route('pelantikanJawatankuasa') . '?tender=' . $tender->uuid;

            return '<a href="' . $sambungUrl . '"
				class="btn btn-sm btn-warning text-white fw-semibold btn-sambung-lantik"
				data-tender-uuid="' . $tender->uuid . '"
				data-peringkat="' . $tenderPeringkat . '">
				Sambung Proses Lantikan
			</a>';
        }

        // Belum ada peringkat — butang merah membuka modal pemilihan.
        $url = route('pelantikanJawatankuasa') . '?tender=' . $tender->uuid;

        return '<button type="button"
			class="btn btn-sm btn-selangor btn-pilih-peringkat"
			data-bs-toggle="modal"
			data-bs-target="#modalPilihPeringkat"
			data-tender-uuid="' . $tender->uuid . '"
			data-lantik-url="' . $url . '">
			Lantik Jawatan Kuasa
		</button>';
    }
}
