<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ingredient extends Model
{
    use HasFactory;

    protected $fillable=[
        'product_id',
        'ingredients'
    ];

    public function milk_products()
    {
        return $this->hasMany(MilkProduct::class,'product_id');
    }
}
