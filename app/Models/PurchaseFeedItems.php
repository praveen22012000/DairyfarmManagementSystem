<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseFeedItems extends Model
{
    use HasFactory;

    protected $fillable=['purchase_id','feed_id','unit_price','purchase_quantity','initial_quantity','stock_quantity','manufacture_date','expire_date'];

    public function feed_consume_items()
    {
        return $this->hasMany(FeedConsumeItems::class,'purchase_feed_item_id');
    }


    public function purchase_feed()
    {
        return $this->belongsTo(PurchaseFeed::class,'purchase_id');
    }

    public function feed()
    {
        return $this->belongsTo(Feed::class,'feed_id');
    }

    public function dispose_feed_items()
    {
        return $this->hasMany(DisposeFeedItems::class,'purchase_feed_item_id');
    }

}
