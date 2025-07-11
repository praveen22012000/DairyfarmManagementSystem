<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class AnimalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'animal_type_id',
        'animal_birthdate',
        'animal_name',
        'ear_tag',
        'status',
        'death_date',
        'sire_id',
        'dam_id',
        'breed_id',
        'color',
        'weight_at_birth',
        'age_at_first_service',
        'weight_at_first_service'
    ];

    public function getAgeAttribute()//This is a custom accessor method in Laravel.//The method name follows this pattern: get{Name}Attribute.
    {
        $birthDate= Carbon::parse($this->animal_birthdate);//Carbon::parse(...) converts the animal_birthdate string (e.g., "2020-03-15") into a Carbon date object.//$this->animal_birthdate means: get the value of the animal_birthdate field for the current animal

        $now= Carbon::now();// Gets the current date and time as a Carbon object. //$now stores today’s date.


        $totalDays=$birthDate->diffInDays($now);//Calculates the total number of days between $birthDate and $now.

        $years=intdiv($totalDays,365);// intdiv(...) performs integer division: it divides without decimal places.//Divides the total number of days by 365 to get the number of full years.

        $remainingDays=$totalDays%365;// % is the modulus operator: it gives the remainder after dividing.

        return "{$years} years and {$remainingDays} days";
    }

    public function vaccine_consume_items()
    {
        return $this->hasMany(VaccineConsumeItems::class,'animal_id');
    }

    public function feed_consume_details()
    {
        return $this->hasMany(FeedConsumeDetails::class,'animal_id');
    }


    public function ProductionMilk()
    {
        return $this->hasMany(ProductionMilk::class,'animal_id');
    }



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

    public function pregnancies()
    {
        return $this->hasMany(Pregnancies::class,'female_cow_id');
    }
}
