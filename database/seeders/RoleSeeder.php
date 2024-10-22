<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Role::create([
            'role_name'=>'FarmOwner'
        ]);

        Role::create([
            'role_name'=>'Veterinarion'
        ]);

        Role::create([
            'role_name'=>'Retailer'
        ]);

        Role::create([
            'role_name'=>'Supplier'
        ]);

        Role::create([
            'role_name'=>'FarmLabore'
        ]);

        Role::create([
            'role_name'=>'GeneralManger'
        ]);

        Role::create([
            'role_name'=>'SalesManger'
        ]);

        

       
    }
}
