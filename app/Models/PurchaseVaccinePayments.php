<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseVaccinePayments extends Model
{
    use HasFactory;

    protected $fillable=['purchase_id','payment_receiver','payment_amount','reference_number','payment_date'];

    
    public function purchase_vaccine()
    {
        return $this->belongsTo(PurchaseVaccine::class,'purchase_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'payment_receiver');
    }
}
