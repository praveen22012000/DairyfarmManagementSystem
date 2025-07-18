<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailorOrder extends Model
{
    use HasFactory;

    protected $fillable=['retailor_id','total_amount','ordered_date','status','payment_status','total_amount','discount_amount','total_payable_amount'];

    protected $casts = [
    'total_amount' => 'decimal:2',
    'discount_amount' => 'decimal:2',
    'total_payable_amount' => 'decimal:2',
];


    public function user()
    {
        return $this->belongsTo(User::class,'retailor_id');
    }

    public function retailor_order_item()
    {
        return $this->hasMany(RetailorOrderItems::class,'order_id');
    }

    public function order_payment()
    {
        return $this->hasOne(OrderPayment::class, 'order_id');
    }

    public function farm_labore()
    {
        return $this->belongsTo(FarmLabore::class,'delivery_person_id');
    }
    
}
