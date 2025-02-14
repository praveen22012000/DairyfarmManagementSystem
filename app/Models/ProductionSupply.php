<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSupply extends Model
{
    use HasFactory;

    protected $fillable=[
        'date',
        'time',
        'entered_by'
    ];
    
    public function ProductionSupplyDetails()
    {
        return $this->hasMany(ProductionSupplyDetails::class,'production_supply_id');
    }
}
