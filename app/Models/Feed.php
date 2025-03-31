<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    
    protected $fillable=['feed_name','manufacturer','unit_type','unit_price'];

    
    

    public function purchase_feed_items()
    {
        return $this->hasMany(PurchaseFeedItems::class,'feed_id');
    }

    public function feed_consume_items()
    {
        return $this->hasMany(FeedConsumeItems::class,'feed_id');
    }
}
