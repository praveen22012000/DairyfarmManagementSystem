<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Carbon; 

class FarmLaboreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('farm_labores')->insert([
            'farm_labore_id' => 11, // replace with actual user ID
            'farm_labore_hire_date' => Carbon::now()->subDays(10)->toDateString(),
            'status' => 'Available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
