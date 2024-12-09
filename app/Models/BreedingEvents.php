<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreedingEvents extends Model
{
    use HasFactory;

    protected $fillable=['female_cow_id',
                        'male_cow_id',
                        'veterinarian_id',
                        'breeding_date',
                        'insemination_type',
                        'notes'
];


//this function create a relationship to the AnimalDetail model
    public function malecow()
    {
        return $this->belongsTo(AnimalDetail::class,'male_cow_id');
    }

    //this function create a relationship to the AnimalDetail model
    public function femalecow()
    {
        return $this->belongsTo(AnimalDetail::class,'female_cow_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'veterinarian_id');
    }

    public function pregnancies()
    {
        return $this->belongsTo(Pregnancies::class,'breeding_id');
    }
}
