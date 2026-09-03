<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StandardChecklistItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'category'              => 'technical',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Senarai Pengalaman Kerja',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/pengalaman-kerja',
                'is_active'             => true,
                'sort_order'            => 1,
            ],
            [
                'category'              => 'technical',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Kerja Dalam Tangan',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/kerja-dalam-tangan',
                'is_active'             => true,
                'sort_order'            => 2,
            ],

            [
                'category'              => 'financial',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Maklumat Profil Petender',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/profil-petender',
                'is_active'             => true,
                'sort_order'            => 1,
            ],
            [
                'category'              => 'financial',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Penyata Bank Terkini (3 Bulan Terakhir) Syarikat',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/penyata-bank',
                'is_active'             => true,
                'sort_order'            => 2,
            ],
            [
                'category'              => 'financial',
                'type'                  => 'standard',
                'title'                 => 'Modal Berbayar',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'            => 3,
            ],
            [
                'category'              => 'financial',
                'type'                  => 'standard',
                'title'                 => 'Kemudahan Kredit (Overdraf, Pinjaman Bank)',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'            => 4,
            ],
            [
                'category'              => 'financial',
                'type'                  => 'standard',
                'title'                 => 'Pengesahan dari Institusi Kewangan ke atas jumlah yang telah dibayar',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'            => 5,
            ],
            [
                'category'              => 'financial',
                'type'                  => 'standard',
                'title'                 => 'Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Jumlah (RM) Kontrak yang pernah diikat)',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'            => 6,
            ],

            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Pengalaman Syarikat Dengan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 3,
            ],
            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 4,
            ],
            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Skop Bekalan Dan Perkhidmatan',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 5,
            ],
            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Salinan Borang KWSP A setiap pekerja bagi bulan caruman terakhir',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 6,
            ],
            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Bilangan Kakitangan',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 7,
            ],
            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Brosur / Risalah',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 8,
            ],
            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Surat pengesahan pendaftaran dengan Pertubuhan Keselamatan Sosial (Perkeso) yang telah dikeluarkan mengikut Akta Keselamatan Sosial Pekerja 1969. Jadual Caruman Bulanan (Borang 8A) dan Resit Bayaran Caruman yang terbaru',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 9,
            ],
            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Cadangan Bertulis',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 10,
            ],
            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Lesen Premis oleh PBT',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 11,
            ],
            [
                'category'           => 'technical',
                'type'               => 'standard',
                'title'              => 'Jadual Pelaksanaan',
                'mechanism_default'  => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'         => 12,
            ],
            [
                'category'              => 'kewangan_kerja',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Lembaran Imbangan',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/lembaran-imbangan',
                'is_active'             => true,
                'sort_order'            => 1,
            ],
            [
                'category'              => 'kewangan_kerja',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Penyata Bulanan / Akaun Bank',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/penyata-bank',
                'is_active'             => true,
                'sort_order'            => 2,
            ],
            [
                'category'              => 'kewangan_kerja',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Bon Atau Saham',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/bon-atau-saham',
                'is_active'             => true,
                'sort_order'            => 3,
            ],
            [
                'category'              => 'kewangan_kerja',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Prestasi Kerja Semasa Petender',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/prestasi-kerja-semasa-petender',
                'is_active'             => true,
                'sort_order'            => 4,
            ],
            [
                'category'              => 'kewangan_kerja',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Senarai Pengalaman Kerja',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/pengalaman-kerja',
                'is_active'             => true,
                'sort_order'            => 5,
            ],
            [
                'category'              => 'kewangan_kerja',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Senarai Kakitangan Teknikal',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/kakitangan-teknikal',
                'is_active'             => true,
                'sort_order'            => 6,
            ],
            [
                'category'              => 'kewangan_kerja',
                'type'                  => 'standard',
                'title'                 => 'Laporan Bank atau Borang CA',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'            => 7,
            ],
            [
                'category'              => 'kewangan_kerja',
                'type'                  => 'standard',
                'title'                 => 'Laporan Penyelia Projek Bagi Kerja Semasa',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'            => 8,
            ],
        ];

        foreach ($items as $item) {
            $exists = DB::table('standard_checklist_items')
                ->where('title', $item['title'])
                ->where('category', $item['category'])
                ->where('type', $item['type'])
                ->exists();

            if ($exists) {
                DB::table('standard_checklist_items')
                    ->where('title', $item['title'])
                    ->where('category', $item['category'])
                    ->where('type', $item['type'])
                    ->update(array_merge($item, [
                        'updated_at' => now(),
                    ]));
            } else {
                DB::table('standard_checklist_items')->insert(array_merge($item, [
                    'uuid'       => (string) Str::uuid(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
