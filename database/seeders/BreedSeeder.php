<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Breed;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Breed::create([
        'breed_name'=>'Holstein'
        ]);

        Breed::create([
        'breed_name'=>'Jersey'
        ]);

        Breed::create([
        'breed_name'=>'Angus'
        ]);

        Breed::create([
        'breed_name'=>'Hereford'
        ]);


    }
}
