<?php

/**
 * Script to add Refund permissions to the permissions table
 * 
 * Usage:
 *   php add_refund_permissions.php
 *   OR
 *   php artisan tinker < add_refund_permissions.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$permissions = [
    [
        'name' => 'Refund:list',
        'display_name' => 'Senarai Pemulangan Semula',
        'group_name' => 'Refund',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Refund:create',
        'display_name' => 'Tambah Pemulangan Semula',
        'group_name' => 'Refund',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Refund:store',
        'display_name' => 'Simpan Pemulangan Semula',
        'group_name' => 'Refund',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Refund:edit',
        'display_name' => 'Kemaskini Pemulangan Semula',
        'group_name' => 'Refund',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Refund:update',
        'display_name' => 'Kemaskini Pemulangan Semula',
        'group_name' => 'Refund',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Refund:delete',
        'display_name' => 'Padam Pemulangan Semula',
        'group_name' => 'Refund',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Refund:show',
        'display_name' => 'Paparan Pemulangan Semula',
        'group_name' => 'Refund',
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

$added = 0;
$skipped = 0;

foreach ($permissions as $permission) {
    // Check if permission already exists
    $exists = DB::table('permissions')
        ->where('name', $permission['name'])
        ->exists();

    if (!$exists) {
        DB::table('permissions')->insert($permission);
        echo "✓ Added permission: {$permission['name']}\n";
        $added++;
    } else {
        echo "⊘ Skipped (already exists): {$permission['name']}\n";
        $skipped++;
    }
}

echo "\n";
echo "Summary:\n";
echo "  Added: {$added}\n";
echo "  Skipped: {$skipped}\n";
echo "  Total: " . count($permissions) . "\n";
echo "\nDone!\n";
