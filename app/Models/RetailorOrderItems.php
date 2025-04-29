<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailorOrderItems extends Model
{
    use HasFactory;

    protected $fillable=['order_id','product_id','ordered_quantity','unit_price'];

    public function milk_product()
    {
        return $this->belongsTo(MilkProduct::class,'product_id');
    }

    public function retailor_order()
    {
        return $this->belongsTo(RetailorOrder::class,'order_id');
    }
}
