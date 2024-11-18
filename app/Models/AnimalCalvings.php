<?php

namespace App\Models;

use App\Models\AnimalDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalCalvings extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'calf_id',
           // 'parent_cow_id',
            'veterinarian_id',
            'calving_date',
            'calving_notes'
       
    ];


    
     // Get the parent animal (cow) for each calving
     //define a relationship a relationship to animal details(parent_cow_id is the parent animal)
     public function parentCow()
     {
         return $this->belongsTo(AnimalDetail::class, 'parent_cow_id');
     }
 
     // Get the calf's details
     //define a relationship a relationship to animal details(calf_id is the child animal)
     public function calf()
     {
         return $this->belongsTo(AnimalDetail::class, 'calf_id');
     }

     //this function establish a realtionship to the user model
     public function user()
     {
        return $this->belongsTo(User::class,'veterinarian_id');
     }
}
