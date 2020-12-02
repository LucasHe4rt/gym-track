<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GymSeeder::class);
        $this->call(InstructorSeeder::class);
        $this->call(ClientsSeeder::class);
        $this->call(EmergencyContactsSeeder::class);
        $this->call(MedicalConditionsSeeder::class);
    }
}
