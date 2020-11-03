<?php

namespace Database\Seeders;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MedicalConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create('pt_BR');

        for ($i = 0; $i <= 100; $i++) {
            DB::table('medical_conditions')->insert([
                'name' => $faker->text(40),
                'description' => $faker->realText(200),
                'medicine' => $faker->text(20),
                'client_id' => random_int(1,100),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
