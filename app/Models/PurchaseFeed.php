<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseFeed extends Model
{
    use HasFactory;

    protected $fillable=['supplier_id','purchase_date','pay_mode','user_id'];

    public function purchase_feed_payment()
    {
        return $this->belongsTo(PurchaseFeedPayment::class,'purchase_id');
    } 

    public function purchase_feed_items()
    {
        return $this->hasMany(PurchaseFeedItems::class,'purchase_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
}
