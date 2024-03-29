<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\Customer;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Obtener todas las regiones
        $regions = Region::all();

        foreach(range(1, 5) as $index) {
            $region = $regions->random();
            $commune = $region->communes()->inRandomOrder()->first();

            Customer::create([
                'dni' => $faker->unique()->regexify('[A-Z0-9]{10}'), // Genera un DNI aleatorio de 10 dígitos
                'id_reg' => $region->id_reg,
                'id_com' => $commune->id_com,
                'email' => $faker->unique()->safeEmail,
                'name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'address' => $faker->address,
                'date_reg' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
