<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefOrganizationType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrganizationTypes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        RefOrganizationType::create([
            'name' => 'ROB: PERSEORANGAN',
            'active' => '1',
            'is_ssm' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefOrganizationType::create([
            'name' => 'ROB: PERKONGSIAN',
            'active' => '1',
            'is_ssm' => '0',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefOrganizationType::create([
            'name' => 'ROC: BERHAD',
            'active' => '1',
            'is_ssm' => '0',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefOrganizationType::create([
            'name' => 'ROC: SENDIRIAN BERHAD',
            'active' => '1',
            'is_ssm' => '0',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefOrganizationType::create([
            'name' => 'ROC: PERKONGSIAN LIABILITI TERHAD',
            'active' => '1',
            'is_ssm' => '0',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefOrganizationType::create([
            'name' => 'ROS: KOPERASI',
            'active' => '1',
            'is_ssm' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefOrganizationType::create([
            'name' => 'ROS: PERTUBUHAN',
            'active' => '1',
            'is_ssm' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefOrganizationType::create([
            'name' => 'ROS: PERSATUAN',
            'active' => '1',
            'is_ssm' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
