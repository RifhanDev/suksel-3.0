<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Ref\HaveNo;
use Database\Seeders\Ref\KaedahPerolehanSeeder;
use Database\Seeders\Ref\KategoriJenisPerolehan;
use Database\Seeders\Ref\OpenTo;
use Database\Seeders\Ref\SumberPeruntukan;
use Database\Seeders\Ref\TypeOfContract;
use Database\Seeders\Ref\TypeOfPemenuhan;
use Database\Seeders\Ref\TypeOfPerolehan;
use Database\Seeders\Ref\TypeOfTender;
use Database\Seeders\Ref\YesNo;
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
            KaedahPerolehanSeeder::class,
            KategoriJenisPerolehan::class,
            YesNo::class,
            SumberPeruntukan::class,
            OpenTo::class,
            HaveNo::class,
            TypeOfContract::class,
            TypeOfPemenuhan::class,
            TypeOfTender::class,
            TypeOfPerolehan::class,
        ]);
    }
}
