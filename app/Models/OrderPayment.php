<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    protected $fillable=['order_id','payment_receipt','transaction_id','payment_date'];

    public function retailor_order()
    {
        return $this->belongsTo(RetailorOrder::class,'order_id');
    }
}
