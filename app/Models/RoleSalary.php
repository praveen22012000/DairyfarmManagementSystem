<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleSalary extends Model
{
    use HasFactory;

    protected $fillable=['role_id','salary'];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
}
