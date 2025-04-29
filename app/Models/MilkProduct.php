<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkProduct extends Model
{
    use HasFactory;

    protected $fillable=[
        'product_name',
        'unit_price'

    ];

    public function retailor_order_item()
    {
        return $this->hasMany(RetailorOrderItems::class,'product_id');
    }

    public function production_supply_details()
    {
        return $this->hasMany(ProductionSupplyDetails::class,'product_id');
    }

    public function ingredients()
    {
        return $this->hasMany(ingredient::class,'product_id');
    }

    public function manufacturer_product()
    {
        return $this->hasMany(ManufacturerProduct::class,'product_id');
    }

    public function manufacturer_product_inventory()
    {
        return $this->hasMany(ManufacturerProductInventory::class,'product_id');
    }
}
