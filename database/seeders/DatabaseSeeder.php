<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Medical;
use App\Models\Regional;
use App\Models\Society;
use App\Models\Spot;
use App\Models\SpotVaccine;
use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        (new Regional([
            'province' => 'Jawa Tengah',
            'district' => 'Banjarnegara'
        ]))->save();

        (new Society([
            'id_card_number' => '3301',
            'name' => 'Heraya',
            'password' => Hash::make('123456'),
            'born_date' => '2000-01-07',
            'gender' => 'male',
            'address' => 'Banjarnegara',
            'regional_id' => 1,
            'login_tokens' => null,
        ]))->save();

        (new Spot([
            'regional_id' => 1,
            'name' => 'Rumah Sakit Heraya',
            'address' => 'Jl Gelang Rakit',
            'serve' => 1,
            'capacity' => 3
        ]))->save();

        (new User([
            'username' => 'heraya',
            'password' => Hash::make("123456")
        ]))->save();
        
        (new Medical([
            'user_id' => 1,
            'spot_id' => 1,
            'role' => 'doctor',
            'name' => 'Dr. Heraya'
        ]))->save();


        (new Vaccine([
            'name' => 'Sinovac'
        ]))->save();
        (new Vaccine([
            'name' => 'AstraZeneca'
        ]))->save();
        (new Vaccine([
            'name' => 'Moderna'
        ]))->save();
        (new Vaccine([
            'name' => 'Pfizer'
        ]))->save();
        (new Vaccine([
            'name' => 'Sinnopharm'
        ]))->save();

        (new SpotVaccine([
            'spot_id' => 1,
            'vaccine_id' => 1
        ]))->save();
        (new SpotVaccine([
            'spot_id' => 1,
            'vaccine_id' => 2
        ]))->save();
        (new SpotVaccine([
            'spot_id' => 1,
            'vaccine_id' => 3
        ]))->save();
        (new SpotVaccine([
            'spot_id' => 1,
            'vaccine_id' => 4
        ]))->save();

    }
}
