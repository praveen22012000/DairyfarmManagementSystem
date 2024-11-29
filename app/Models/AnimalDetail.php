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


    //<!-- this is the start of the self-join relationship-->

      // Relationship to get the father of an animal
    public function sire()
    {
        return $this->belongsTo(AnimalDetails::class, 'sire_id');
    }

      // Relationship to get the mother of an animal
    public function dam()
    {
        return $this->belongsTo(AnimalDetails::class, 'dam_id');
    }

    // Relationship to get children where the animal is the father
    public function offspringAsSire()
    {
        return $this->hasMany(AnimalDetails::class, 'sire_id');
    }

    // Relationship to get children where the animal is the mother
    public function offspringAsDam()
    {
        return $this->hasMany(AnimalDetails::class, 'dam_id');
    }

   // <!-- this is the end of the self join relationship>


    public function AnimalType()
    {
        return $this->belongsTo(AnimalType::class);
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

    //define a relationship to the BreedingEvents table
    public function maleBreedingEvents()
    {
        return $this->hasMany(BreedingEvents::class,'male_cow_id');
    }

      //define a relationship to the BreedingEvents table
    public function femaleBreedingEvents()
    {
        return $this->hasMany(BreedingEvents::class,'female_cow_id');
    }
}
