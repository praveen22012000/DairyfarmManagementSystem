<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

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

    public function getExperienceAttribute()
    {
        $hireDate = Carbon::parse($this->hire_date);
        $now = Carbon::now();

        // Calculate the total difference in days
        $totalDays = $hireDate->diffInDays($now);

        // Calculate full years and remaining days
        $years = intdiv($totalDays, 365); // Integer division to get full years
        $remainingDays = $totalDays % 365; // Remaining days after full years

        return "{$years} years and {$remainingDays} days";
    }

    public function getAgeAttribute()
    {
        $birthDate= Carbon::parse($this->birth_date);

        $now= Carbon::now();


        $totalDays=$birthDate->diffInDays($now);

        $years=intdiv($totalDays,365);
        $remainingDays=$totalDays%365;

        return "{$years} years and {$remainingDays} days";
    }

    public function user()
    {
        return $this->belongsTo(User::class,'veterinarian_id');
    }
}
