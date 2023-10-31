<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Database extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Specify the tables you want to truncate
        $tables = [
            'edu_versions',
            'edu_classes',
            'sections',
            'subjects',
            'fee_frequencies',
            'academic_fee_groups',
            // Add more table names here
        ];

        // Truncate the specified tables
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // Now, you can seed the tables here as well
        $this->call([
            DatabaseSeeder::class,
            
        ]);
    }
}
