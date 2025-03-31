<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedConsumeDetails extends Model
{
    use HasFactory;

    public function feed_consume_item()
    {
        return $this->hasMany(FeedConsumeItems::class,'feed_consume_detail_id');
    }

   

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
