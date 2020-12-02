<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientsSeeder extends Seeder
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
                'email' => $faker->email,
                'sex' => random_int(0, 1) === 1? 'Masculino' : 'Feminino',
                'password' => Hash::make('password'),
                'birthday' => $faker->dateTimeThisCentury(),
                'neighborhood' => $faker->streetAddress,
                'street' => $faker->streetName,
                'city' => $faker->city,
                'number' => $faker->buildingNumber,
                'zipcode' => $faker->postcode,
                'complement' => random_int(0,2) === 1? $faker->secondaryAddress: null,
                'phone' => $faker->phoneNumber,
                'blood' => $faker->randomLetter,
                'height' => (0.01 * random_int(40, 99)),
                'weight' => random_int(50, 150) + (0.01 * random_int(0, 99)),
                'gym_id' => random_int(1,100),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            DB::table('clients')->insert($contact);
        }
    }
}
