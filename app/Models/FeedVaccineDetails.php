<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedVaccineDetails extends Model
{
    use HasFactory;

    protected $fillable=['name','manufacturer','unit_type','unit_price','type'];
}
