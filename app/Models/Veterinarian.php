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
        'doctor_hire_date',
      
        'license_number',
        
      'veterinarian_id'
       
    ];

    public function getExperienceAttribute()
    {
        $hireDate = Carbon::parse($this->doctor_hire_date);
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
        $birthDate= Carbon::parse($this->doctor_birth_date);// This line gets the animal's or person's birth date from the model.

        $now= Carbon::now();//This gets the current date and time using Carbon.


        $totalDays=$birthDate->diffInDays($now);//This calculates the total number of days between the birth date and today.

        $years=intdiv($totalDays,365);// This divides the total days by 365 to get the number of whole years.
        $remainingDays=$totalDays%365;// This calculates the remaining days after subtracting full years.

        return "{$years} years and {$remainingDays} days";// This returns a string like 2 years and 18 days as the final age value.
    }

    public function user()
    {
        return $this->belongsTo(User::class,'veterinarian_id');
    }
}
