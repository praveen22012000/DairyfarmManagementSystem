<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialization',
        'hire_date',
        'birth_date',
        'license_number',
        'gender',
        'salary',
      'veterinarian_id'
       
    ];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
