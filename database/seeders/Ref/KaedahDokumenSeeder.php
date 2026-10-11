<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefKaedahDokumen;
use Illuminate\Database\Seeder;

class KaedahDokumenSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'code' => 'online',
                'name' => 'Bayaran Dokumen Secara Online',
                'description' => 'Syarikat membuat bayaran dokumen melalui Sistem Tender Online Selangor. Dokumen boleh dimuat turun melalui sistem atau diambil di pejabat/kaunter agensi mengikut Syarat Tender.',
                'attention' => null,
                'active' => true,
                'skips_to_penyediaan_iklan' => true,
            ],
            [
                'code' => 'online_penilaian',
                'name' => 'Bayaran Dokumen dan Penilaian secara online',
                'description' => 'Syarikat membayar dokumen, kemudian mengisi dan menghantar dokumen penilaian melalui Sistem Tender Online Selangor.',
                'attention' => null,
                'active' => true,
                'skips_to_penyediaan_iklan' => false,
            ],
            [
                'code' => 'manual',
                'name' => 'Pembelian & Bayaran Manual di Agensi (Iklan Sahaja)',
                'description' => 'Sistem Tender Online Selangor hanya digunakan untuk paparan iklan. Syarikat tidak boleh membuat pembelian atau bayaran dokumen melalui sistem.',
                'attention' => 'PERHATIAN: Jika pilihan ini dipilih, fungsi pembelian dan pembayaran dokumen melalui Sistem Tender Online Selangor tidak akan disediakan kepada syarikat.',
                'active' => true,
                'skips_to_penyediaan_iklan' => true,
            ],
            [
                'code' => 'percuma',
                'name' => 'Dokumen Percuma',
                'description' => 'Tiada bayaran diperlukan. Harga dokumen ditetapkan kepada RM0.00.',
                'attention' => null,
                'active' => false,
                'skips_to_penyediaan_iklan' => false,
            ],
        ];

        foreach ($rows as $row) {
            RefKaedahDokumen::query()->updateOrCreate(
                ['code' => $row['code']],
                $row
            );
        }
    }
}
