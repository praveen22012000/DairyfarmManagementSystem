<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedConsumeItems extends Model
{
    use HasFactory;

    protected $fillable=['consumed_quantity','notes','feed_id','purchase_feed_item_id','feed_consume_detail_id'];

    public function feed_consume_details()
    {
        return $this->belongsTo(FeedConsumeDetails::class,'feed_consume_detail_id');
    }

    public function feed()
    {
        return $this->belongsTo(Feed::class,'feed_id');
    }

    public function purchase_feed_items()
    {
        return $this->belongsTo(PurchaseFeedItems::class,'purchase_feed_item_id');
    }
}
