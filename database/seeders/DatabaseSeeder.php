<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Ref\HaveNo;
use Database\Seeders\Ref\JustifikasiPemilihanPembekal;
use Database\Seeders\Ref\KaedahPerolehanSeeder;
use Database\Seeders\Ref\KategoriJenisPerolehan;
use Database\Seeders\Ref\OpenTo;
use Database\Seeders\Ref\OrganizationTypes;
use Database\Seeders\Ref\SumberPeruntukan;
use Database\Seeders\Ref\TypeOfContract;
use Database\Seeders\Ref\TypeOfPemenuhan;
use Database\Seeders\Ref\TypeOfPerolehan;
use Database\Seeders\Ref\TypeOfTender;
use Database\Seeders\Ref\YesNo;
use Database\Seeders\CreateAdminUser;
use Database\Seeders\UserSeeder;
use Database\Seeders\CodesSeeder;
use Database\Seeders\StandardChecklistItemSeeder;
use Database\Seeders\RefLokalitisSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RefLokalitisSeeder::class,
            KaedahPerolehanSeeder::class,
            KategoriJenisPerolehan::class,
            YesNo::class,
            SumberPeruntukan::class,
            OpenTo::class,
            HaveNo::class,
            JustifikasiPemilihanPembekal::class,
            TypeOfContract::class,
            TypeOfPemenuhan::class,
            TypeOfTender::class,
            TypeOfPerolehan::class,
            OrganizationTypes::class,
            UserSeeder::class,
            CreateAdminUser::class,
            OrganizationTypesSeeder::class,
            OrganizationUnitsSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionSeeder2027::class,
            PermissionRoleSeeder::class,
            StosRolePermissionSeeder::class,
            CodesSeeder::class,
            StandardChecklistItemSeeder::class,
        ]);
    }
}
