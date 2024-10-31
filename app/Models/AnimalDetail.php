<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'animal_type_id',
        'animal_birthdate',
        'animal_name',
        'ear_tag',
        'sire_id',
        'dam_id',
        'breed_id',
        'color',
        'weight_at_birth',
        'age_at_first_service',
        'weight_at_first_service'
    ];

    public function AnimalType()
    {
        return $this->belongsTo(AnimalType::class);
    }

    public function Breed()
    {
        return $this->belongsTo(Breed::class);
    }

    // Define a relationship to AnimalCalvings as a parent cow
    public function calvings()
    {
        return $this->hasMany(AnimalCalvings::class, 'parent_cow_id');
    }

    // Define a relationship to AnimalCalvings as a calf
    public function asCalf()
    {
        return $this->hasOne(AnimalCalvings::class, 'calf_id');
    }
}
