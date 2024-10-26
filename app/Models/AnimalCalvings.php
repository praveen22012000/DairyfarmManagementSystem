<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalCalvings extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'calf_id',
            'parent_cow_id',
            'calving_date',
            'calving_notes'
       
    ];


    public function AnimalDetail()
    {
        return $this->belongsTo(AnimalDetail::class);
    }
}
