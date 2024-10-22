<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AnimalType;

class AnimalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AnimalType::create([
            'Animal_Type'=>'Cow',
            
        ]);

        AnimalType::create([
            'Animal_Type'=>'Heifer',
            
        ]);

        AnimalType::create([
            'Animal_Type'=>'Bull',
            
        ]);

        AnimalType::create([
            'Animal_Type'=>'BullCalf',
            
        ]);
    }
}
