<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFile = database_path('Seeder/data codes.sql');
        
        if (!file_exists($sqlFile)) {
            $this->command->error("SQL file not found: {$sqlFile}");
            return;
        }
        
        $content = file_get_contents($sqlFile);
        
        $pattern = "/INSERT INTO `codes` VALUES \((\d+), '([^']+)', '([^']*(?:''[^']*)*)', '([^']+)', '([^']+)', '([^']+)'\);/";
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        if (empty($matches)) {
            $this->command->error("No records found in SQL file");
            return;
        }
        
        $this->command->info("Found " . count($matches) . " records to seed");
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('codes')->truncate();
        
        $data = [];
        foreach ($matches as $match) {
            $description = str_replace("''", "'", $match[3]);
            $description = str_replace("\r\n", "\n", $description);
            
            $data[] = [
                'id' => (int)$match[1],
                'code' => $match[2],
                'description' => $description,
                'type' => $match[4],
                'created_at' => $match[5],
                'updated_at' => $match[6],
            ];
            
            if (count($data) >= 100) {
                DB::table('codes')->insert($data);
                $data = [];
            }
        }
        
        if (!empty($data)) {
            DB::table('codes')->insert($data);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info("Successfully seeded " . count($matches) . " codes");
    }
}
