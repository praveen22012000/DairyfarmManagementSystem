<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposeFeedDetaills extends Model
{
    use HasFactory;

    protected $fillable=['dispose_date','dispose_time','user_id'];

    public function dispose_feed_items()
    {
        return $this->hasMany(DisposeFeedItems::class,'dispose_feed_detail_id');
    }
}
