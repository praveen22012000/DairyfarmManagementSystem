<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposeFeedItems extends Model
{
    use HasFactory;

    protected $fillable=['purchase_feed_item_id','dispose_feed_detail_id','dispose_quantity','reason_for_dispose'];

    public function dispose_feed_details()
    {
        return $this->belongsTo(DisposeFeedDetaills::class,'dispose_feed_detail_id');
    }

    public function purchase_feed_items()
    {
        return $this->belongsTo(PurchaseFeedItems::class,'purchase_feed_item_id');
    }
}
