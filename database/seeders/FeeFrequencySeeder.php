<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FeeFrequencySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $frequencyData = [
            [
                'freq_hash_id' => $faker->uuid,
                'freq_name' => 'Only Once',
                'no_of_installment' => 1,
                'installment_period' => 'Only Once',
                'freq_status' => 1,
            ],
            [
                'freq_hash_id' => $faker->uuid,
                'freq_name' => 'Monthly',
                'no_of_installment' => 12,
                'installment_period' => 'Monthly',
                'freq_status' => 1,
            ],
            [
                'freq_hash_id' => $faker->uuid,
                'freq_name' => 'Quarterly',
                'no_of_installment' => 4,
                'installment_period' => 'Quarterly',
                'freq_status' => 1,
            ],
            [
                'freq_hash_id' => $faker->uuid,
                'freq_name' => 'Half Yearly',
                'no_of_installment' => 2,
                'installment_period' => 'Half Yearly',
                'freq_status' => 1,
            ],
            [
                'freq_hash_id' => $faker->uuid,
                'freq_name' => 'Yearly',
                'no_of_installment' => 1,
                'installment_period' => 'Yearly',
                'freq_status' => 1,
            ],
            // Add more data for other frequencies as needed
        ];

        DB::table('fee_frequencies')->insert($frequencyData);
    }
}
