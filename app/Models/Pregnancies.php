<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregnancies extends Model
{
    use HasFactory;

    protected $fillable=[
                    'breeding_id',
                    'female_cow_id',
                    'male_cow_id',
                    'veterinarian_id',
                    'pregnancy_status',
                    'estimated_calving_date',
                    'confirmation_date'
    ];

    //this function establish the relationship to the breedingEvents table
    public function breeding_event()
    {
        return $this->belongsTo(BreedingEvents::class,'breeding_id');
    }

    //this function establish the relationship to the AnimalDetail model
    public function AnimalDetail()
    {
        return $this->belongsTo(AnimalDetail::class,'female_cow_id');
    }

    //this function establish the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class,'veterinarian_id');
    }


}
