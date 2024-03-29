<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegionCommunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reg_com=[
            'Andalucía' => ['Sevilla', 'Málaga', 'Cádiz', 'Granada', 'Córdoba'],
            'Cataluña' => ['Barcelona', 'Girona', 'Lleida', 'Tarragona', 'Tosa de Mar'],
            'Comunidad de Madrid' => ['Madrid', 'Alcalá de Henares', 'Móstoles', 'Fuenlabrada', 'Leganés'],
            'Comunidad Valenciana' => ['Valencia', 'Alicante', 'Castellón', 'Elche', 'Torrevieja'],
            'País Vasco' => ['Bilbao', 'Vitoria-Gasteiz', 'Donostia-San Sebastián', 'Barakaldo', 'Getxo'],
            'Galicia' => ['A Coruña', 'Vigo', 'Santiago de Compostela', 'Lugo', 'Ourense'],
            'Castilla y León' => ['Valladolid', 'León', 'Burgos', 'Salamanca', 'Zamora'],
            'Canarias' => ['Las Palmas de Gran Canaria', 'Santa Cruz de Tenerife', 'La Laguna', 'Telde', 'Arrecife'],
            'Aragón' => ['Zaragoza', 'Huesca', 'Teruel', 'Calatayud', 'Alcañiz'],
            'Islas Baleares' => ['Palma de Mallorca', 'Ibiza', 'Menorca', 'Formentera', 'Santa Eulària des Riu'],
        ];

        foreach($reg_com as $regionName => $communes){
            $region= Region::create(['description'=> $regionName]);

            foreach($communes as $communeName){
                $region->communes()->create(['description'=> $communeName]);
            }
        }
    }
}
