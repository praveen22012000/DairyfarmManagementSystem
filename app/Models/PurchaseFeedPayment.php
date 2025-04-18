<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseFeedPayment extends Model
{
    use HasFactory;

    protected $fillable=['purchase_id','payment_receiver','payment_amount','reference_number','payment_date'];

    public function purchase_feed()
    {
        return $this->belongsTo(PurchaseFeed::class,'purchase_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'payment_receiver');
    }
}
