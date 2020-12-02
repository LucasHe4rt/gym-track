<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmergencyContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 100; $i++){
            $faker = Factory::create('pt_BR');
            $contact = [
                'name' => $faker->name,
                'neighborhood' => $faker->streetAddress,
                'street' => $faker->streetName,
                'city' => $faker->city,
                'number' => $faker->buildingNumber,
                'zipcode' => $faker->postcode,
                'complement' => random_int(0,2) === 1? $faker->secondaryAddress: null,
                'phone' => $faker->phoneNumber,
                'client_id' => random_int(1,100),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            DB::table('emergency_contacts')->insert($contact);
        }
    }
}
