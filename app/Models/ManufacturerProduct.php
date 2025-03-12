<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturerProduct extends Model
{
    use HasFactory;

    protected $fillable=['manufacturer_id','product_id','quantity','manufacture_date','expire_date','user_id','stock_quantity','initial_quantity_of_product'];

    public function dispose_milk_product()
    {
        return $this->hasMany(DisposeMilkProduct::class,'manufacturer_product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class,'manufacturer_id');
    }

    public function milk_product()
    {
    return $this->belongsTo(MilkProduct::class, 'product_id');
    }

}
