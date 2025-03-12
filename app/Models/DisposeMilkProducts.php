<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposeMilkProducts extends Model
{
    use HasFactory;

    protected $fillable=['manufacturer_product_id','user_id','date','reason_for_dispose','dispose_quantity'];

    public function manufacture_proudct()
    {
        return $this->belongsTo(ManufacturerProduct::class,'manufacturer_product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
