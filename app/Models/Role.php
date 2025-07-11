<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class);
    }

   // will be delete in the duture 
 /*   public function salary()
    {
        return $this->hasOne(Salary::class,'role_id');
    }*/

    public function role_salary()
    {
        return $this->hasOne(RoleSalary::class,'role_id');
    }
}
