<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSupplyDetails extends Model
{
    use HasFactory;

    protected $fillable=[
        'production_supply_id',
        'production_milk_id',
        'consumed_quantity',
        'product_id'
    ];

    public function production_milk()
    {
       
        return $this->belongsTo(ProductionMilk::class,'production_milk_id');
    }

    public function milk_product()
    {
        return $this->belongsTo(MilkProduct::class,'product_id');
    }

    public function production_supply()
    {
        return $this->belongsTo(ProductionSupply::class,'production_supply_id');
    }
}
